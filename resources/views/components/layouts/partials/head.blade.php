{{--
    رأس المستند.
    يضبط الترميز وعنوان الصفحة وخطوط تاجوال وجاكرتا
    ثم يحمّل أصول Vite وأنماط Livewire ويخفي عناصر x-cloak.
--}}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- بيانات السيو: الوصف، canonical، hreflang، Open Graph، JSON-LD.
         ننشئ هنا المتغيرين $settings و $seo ليكون $seo متاحاً للعنوان أدناه،
         ثم نستدعي الجزء الذي يطبع الوسوم. --}}
    @php
        $settings = $settings ?? \App\Models\RestaurantSetting::current();
        $seo = new \App\Support\SeoMetadata($settings);
    @endphp
    @include('components.layouts.partials.seo')

    <title>{{ $title ?? $seo->title() }}</title>

    {{-- إعدادات تطبيق الويب التقدمي: تتيح تثبيت الموقع على شاشة الجهاز الرئيسية --}}
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#E07513">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="{{ __('messages.brand.name') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icons/icon-192.png') }}">

    {{-- تحميل مسبق لشعار الهيدر لأنه يظهر أعلى الصفحة في كل الصفحات --}}
    <link rel="preload" as="image" href="{{ asset('images/logo2.webP') }}" fetchpriority="high">

    @if (request()->routeIs('home'))
        {{-- تحميل مسبق لخلفية الصفحة الرئيسية لأنها أهم عنصر بصري عند أول تحميل --}}
        <link rel="preload" as="image" href="{{ asset('images/home/home_logo.webp') }}" fetchpriority="high">
    @endif

    {{-- خطوط عربية ولاتينية مع اتصال مبكر بنطاق الخطوط --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Tajawal:wght@400;500;700;800;900&display=swap"
        rel="stylesheet">

    {{-- يمنع وميض القوائم قبل إقلاع Alpine --}}
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
{{-- رمز CSRF المطلوب لطلب حفظ إعدادات الكوكيز --}}
    <meta name= "csrf-token" content= "{{ csrf_token() }}" >

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    {{-- معرّفات أدوات التتبّع (تُدار من Filament) — تُقرأ فقط عند تحميل السكربت بعد الموافقة --}}
    @php($tracking = $settings ?? \App\Models\RestaurantSetting::current())
    <script>
        window.__analyticsId = @json($tracking->google_analytics_id);
        window.__metaPixelId = @json($tracking->meta_pixel_id);
    </script>

    {{-- حالة الموافقة + بوابة السكربتات (قبل أي سكربت تتبّع) --}}
     @include( 'components.layouts.partials.cookie-consent-scripts' )
</head>
