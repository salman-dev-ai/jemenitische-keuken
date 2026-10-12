{{--
    رأس المستند.
    يضبط الترميز وعنوان الصفحة وخطوط تاجوال وجاكرتا
    ثم يحمّل أصول Vite وأنماط Livewire ويخفي عناصر x-cloak.
--}}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? __('messages.brand.name') . ' - ' . __('messages.brand.slogan') }}</title>

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

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
