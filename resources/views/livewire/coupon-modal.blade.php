<?php

use App\Models\Customer;
use App\Models\Promotion;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use function Livewire\Volt\{state, on};

/**
 * مكوّن Volt لنافذة الكوبون.
 * يلتقط حدث الفتح من العروض. للعميل المسجل يطبق الكوبون مباشرة،
 * وللجديد يعرض التسجيل ثم ينشئ العميل. لا يعيد التوجيه ولا ينفذ المطالبة هنا.
 */

// تحتفظ هذه الحالة بإظهار النافذة وحقول الضيف ورمز الكوبون الحالي.
state([
    'show'        => false,
    'name'        => '',
    'phone'       => '',
    'email'       => '',
    'address'     => '',
    'city'        => '',
    'postal_code' => '',
    'couponCode'  => '',
]);

// يلتقط حدث استخدم الآن من بطاقات العروض ويقرر مسار العميل.
on(['open-coupon-modal' => function (int $promotionId, string $action = 'use') {
    $promotion = Promotion::with('coupon')->find($promotionId);

    if (! $promotion || ! $promotion->coupon) {
        return;
    }

    $this->couponCode = $promotion->coupon->code;
    $this->resetForm();

    // عميل مسجل ينسخ الرمز أو يطبقه دون فتح نموذج التسجيل.
    if ($this->currentCustomer()) {
        if ($action === 'copy') {
            // يطلب من الواجهة الأمامية نسخ رمز الكوبون الحالي إلى الحافظة.
            $this->dispatch('coupon-copied', code: $this->couponCode);
        } else {
            // يطبق رمز الكوبون مباشرة على السلة ثم يوقف مسار الضيف.
            $this->applyToCart();
        }

        return;
    }

    // للعميل الجديد يفتح نموذج التسجيل سواء كان الفعل نسخاً أو استخداماً.
    $this->show = true;
}]);

// يتحقق من بيانات الضيف ثم ينشئ عميلا ويحفظ كوكي التذكر.
$submitGuest = function (): void {
    $validated = $this->validate([
        'name' => 'required|string|min:3|max:255',
        'phone' => [
            'required',
            'string',
            'min:7',
            'max:30',
            'regex:/^[0-9٠١٢٣٤٥٦٧٨٩+\-().\s]+$/',
        ],
        'email'       => 'nullable|email|max:150',
        'address'     => 'nullable|string|max:255',
        'city'        => 'nullable|string|max:100',
        'postal_code' => 'nullable|string|max:20',
    ]);

    $customer = Customer::create([
        'name'           => $validated['name'],
        'phone'          => $this->normalizePhone($validated['phone']),
        'email'          => $validated['email'] ?: null,
        'address'        => $validated['address'] ?: null,
        'city'           => $validated['city'] ?: null,
        'postal_code'    => $validated['postal_code'] ?: null,
        'remember_token' => Str::random(60),
    ]);

    Cookie::queue(
        'remember_customer_token',
        $customer->remember_token,
        60 * 24 * 90,
    );

    $this->dispatch('customer-remembered');
    $this->applyToCart();
};

// يغلق النافذة ثم يرسل حدث تطبيق الكوبون الحالي إلى السلة.
$applyToCart = function (): void {
    $this->show = false;
    $this->dispatch('apply-coupon-to-cart', code: $this->couponCode);
};

// يعيد سجل العميل الحالي إن وجد رمز التذكر داخل الكوكي.
$currentCustomer = function (): ?Customer {
    $token = request()->cookie('remember_customer_token');

    return $token
        ? Customer::where('remember_token', $token)->first()
        : null;
};

// يفرغ حقول نموذج التسجيل كلها قبل فتح النافذة من جديد.
$resetForm = function (): void {
    $this->name        = '';
    $this->phone       = '';
    $this->email       = '';
    $this->address     = '';
    $this->city        = '';
    $this->postal_code = '';
};

// يحول الأرقام العربية إلى إنجليزية ثم يزيل الفراغات والرموز الزائدة.
$normalizePhone = function (string $phone): string {
    $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
    $ascii  = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    return preg_replace('/[\s\-\(\)]+/', '', str_replace($arabic, $ascii, $phone)) ?: '';
};

?>

<div>
    {{-- يخفي عقد Alpine قبل التهيئة حتى لا تومض النافذة عند التحميل. --}}
    <style>[x-cloak]{display:none !important}</style>

    {{-- يستدعي شجرة العرض البصرية ويبقي منطق Volt كله في هذا الملف. --}}
    @include('livewire.modals.coupon.modal')
</div>
