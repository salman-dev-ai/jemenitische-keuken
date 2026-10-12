{{--
    كتلة بيانات التذييل فقط.
    تجهّز الترجمة الاحتياطية والهاتف والواتساب والعنوان والخريطة وساعات العمل،
    ثم تشاركها في footerLayout لأن عزل @include لا يُرجع المتغيرات للأب.
--}}
@php
    $translate = static function (string $key, string $fallback): string {
        return \Illuminate\Support\Facades\Lang::has($key) ? __($key) : $fallback;
    };

    $phone = trim((string) ($settings?->phone ?? ''));
    $whatsapp = trim((string) ($settings?->whatsapp ?? ''));
    $email = trim((string) ($settings?->email ?? ''));
    $mapsLink = trim((string) ($settings?->google_maps_link ?? ''));
    $city = trim((string) ($settings?->city ?? ''));
    $postalCode = trim((string) ($settings?->postal_code ?? ''));
    $address = trim((string) ($settings?->localized_address ?? ''));
    $openingHours = is_array($settings?->opening_hours ?? null) ? $settings->opening_hours : [];

    $phoneHref = preg_replace('/[^0-9+]/', '', $phone) ?: '';
    $whatsappHref =
        str_starts_with($whatsapp, 'http://') || str_starts_with($whatsapp, 'https://')
            ? $whatsapp
            : 'https://wa.me/' . preg_replace('/[^0-9]/', '', $whatsapp);

    // غيّر المسار إذا كان اسم صورة المدينة في مشروعك مختلفاً.
    $backgroundImage = asset('images/logo-transparent.webp');

    view()->share(
        'footerLayout',
        compact(
            'translate',
            'phone',
            'whatsapp',
            'email',
            'mapsLink',
            'city',
            'postalCode',
            'address',
            'openingHours',
            'phoneHref',
            'whatsappHref',
            'backgroundImage',
        ),
    );
@endphp
