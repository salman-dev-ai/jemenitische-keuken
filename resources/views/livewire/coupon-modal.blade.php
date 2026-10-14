<?php

use App\Models\Customer;
use App\Models\Promotion;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use function Livewire\Volt\{state, on};

/**
 * resources/views/livewire/coupon-modal.blade.php
 *
 * مكوّن Volt لنافذة الكوبون (المنطق فقط، والواجهة في modals/coupon/modal.blade.php).
 * - عميل مسجل: ينسخ الكوبون أو يطبقه مباشرة.
 * - عميل جديد: تفتح نافذة التسجيل، وبعد الحفظ ينفذ الفعل الذي ضغط عليه (نسخ أو استخدام).
 */

state([
    'show'          => false,
    'promotionId'   => 0,
    'pendingAction' => 'use', // copy | use
    'name'          => '',
    'phone'         => '',
    'email'         => '',
    'address'       => '',
    'city'          => '',
    'postal_code'   => '',
    'couponCode'    => '',
]);

// يلتقط حدث الفتح من بطاقات العروض ويقرر المسار.
on(['open-coupon-modal' => function (int $promotionId, string $action = 'use') {
    $promotion = Promotion::with('coupon')->find($promotionId);

    if (! $promotion || ! $promotion->coupon) {
        return;
    }

    $this->promotionId   = $promotionId;
    $this->pendingAction = $action === 'copy' ? 'copy' : 'use';
    $this->couponCode    = $promotion->coupon->code;
    $this->resetForm();

    // عميل مسجل: ينفذ الفعل مباشرة بدون نموذج.
    if ($this->currentCustomer()) {
        $this->runPendingAction();
        return;
    }

    // عميل جديد: يفتح نموذج التسجيل.
    $this->show = true;
}]);

// ينفذ الفعل المعلّق: نسخ الكود أو تطبيقه على السلة.
$runPendingAction = function (): void {
    if ($this->pendingAction === 'copy') {
        $this->show = false;
        // promotionId يجعل علامة "تم النسخ" تظهر على الكارد المحدد فقط.
        $this->dispatch('coupon-copied',
            promotionId: $this->promotionId,
            code: $this->couponCode,
        );
        return;
    }

    $this->applyToCart();
};

// يتحقق من بيانات الضيف وينشئ العميل ثم ينفذ الفعل المعلّق.
$submitGuest = function (): void {
    $validated = $this->validate([
        'name'  => 'required|string|min:3|max:255',
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
    $this->runPendingAction();
};

// يعيد جلب الكود من قاعدة البيانات (لا يثق بالحقل القادم من المتصفح).
$applyToCart = function (): void {
    $promotion = Promotion::with('coupon')->find($this->promotionId);

    if (! $promotion || ! $promotion->coupon) {
        return;
    }

    $this->show = false;
    $this->dispatch('apply-coupon-to-cart', code: $promotion->coupon->code);
};

// العميل الحالي من كوكي التذكر.
$currentCustomer = function (): ?Customer {
    $token = request()->cookie('remember_customer_token');

    return $token
        ? Customer::where('remember_token', $token)->first()
        : null;
};

$resetForm = function (): void {
    $this->name        = '';
    $this->phone       = '';
    $this->email       = '';
    $this->address     = '';
    $this->city        = '';
    $this->postal_code = '';
};

// يحول الأرقام العربية إلى إنجليزية ويزيل الفراغات والرموز.
$normalizePhone = function (string $phone): string {
    $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
    $ascii  = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    return preg_replace('/[\s\-\(\)]+/', '', str_replace($arabic, $ascii, $phone)) ?: '';
};

?>

<div>
    <style>[x-cloak]{display:none !important}</style>

    @include('livewire.modals.coupon.modal')
</div>
