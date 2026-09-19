@props(['settings' => null])


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
    class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? __('messages.brand.name') . ' - ' . __('messages.brand.slogan') }}</title>

    <!-- الخطوط العربي واللاتينية الفاخرة -->
    <link rel="preconnect" href="https ://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Tajawal:wght@400;500;700;800;900&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-['Tajawal'] bg-[#FDFBF7] text-[#2C1810] antialiased selection:bg-[#E07513] selection:text-white">


    <header x-data="{
        mobileOpen: false,
        languageOpen: false,
        activeSection: 'home',
        /* متغير لتتبع القسم النشط */
        toggleMobile() {
            this.mobileOpen = !this.mobileOpen;
            this.languageOpen = false;
        },
        toggleLanguage() {
            this.languageOpen = !this.languageOpen;
            this.mobileOpen = false;
        },
        closeMenus() {
            this.mobileOpen = false;
            this.languageOpen = false;
        }
    }" @keydown.escape.window="closeMenus()"
        class="sticky top-0 z-50 w-full overflow-x-clip border-b border-gray-200/30 bg-white/60 shadow-sm backdrop-blur-lg transition-all duration-300">

        <div class="relative w-full text-gray-800" dir="ltr">

            {{-- النقشة  (تظهر في الجهة المعاكسة للشعار - أقصى اليمين) --}}

            <div class="absolute inset-y-0 right-0 w-48 lg:w-54 pointer-events-none  bg-no-repeat bg-right bg-contain z-0"
                style="background-image: url('{{ asset('images/pattern.webp') }}');">
            </div>

            <div class="mx-auto max-w-7xl px-3 py-2 sm:px-6 lg:px-8 relative z-10">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-2.5 lg:gap-6">

                    {{-- الصف الأول: الشعار + الأزرار (في الجوال) --}}
                    <div class="flex items-center justify-between w-full lg:w-auto">

                        {{-- الشعار البارز والدائري مع شادو خفيف --}}
                        <a href="#home" @click="activeSection = 'home'"
                            class="group flex shrink-0 items-center transition-transform hover:scale-105">
                            <div
                                class="relative p-0 rounded-full bg-white/90 border border-[#E07513]/40 shadow-md shadow-[#E07513]/15  ring-white/60 z-10 backdrop-blur-sm">
                                <img src="{{ asset('images/image.png') }}" alt="المطبخ اليمني"
                                    class="h-12 w-12 sm:h-14 sm:w-14 lg:h-26 lg:w-26 object-cover rounded-full">
                            </div>
                        </a>

                        {{-- أزرار التفاعل للجوال فقط --}}
                        <div class="flex items-center gap-1.5 sm:gap-2 lg:hidden">

                            <!-- زر الواتساب (يومض) -->
                            <a href="https://wa.me/+769771924870" target="_blank"
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#25D366] text-white shadow-md shadow-[#25D366]/40 transition-all hover:scale-110 animate-pulse">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" />
                                </svg>
                            </a>

                            <!-- زر الطلب Thuisbezorgd -->
                            <a href="https://www.thuisbezorgd.nl/menu/jemenitische-keuken-restaurant#pre"
                                target="_blank"
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-gray-200 bg-white/50 p-1.5 shadow-sm transition-all hover:scale-105">
                                <img src="{{ asset('images/thuisbezorgd.png') }}" alt="Thuisbezorgd"
                                    class="h-full w-full object-contain" />
                            </a>

                            <!-- زر الحجز -->
                            <a href="#reservation"
                                class="inline-flex h-9 shrink-0 items-center justify-center whitespace-nowrap rounded-xl bg-gradient-to-r from-[#E07513] to-[#B85709] px-3 text-[11px] font-bold text-white shadow-md shadow-[#E07513]/25 transition-all animate-pulse">
                                {{ __('messages.nav.bookTableBtn') }}
                            </a>

                            <!-- زر تبديل اللغة -->

                            <div class="relative shrink-0" x-data="{ open: false }">
                                <button @click="open = !open" @keydown.escape.window="open = false" type="button"
                                    class="flex h-10 items-center gap-1.5 rounded-xl border border-gray-200 bg-white/50 px-3 text-xs font-bold text-gray-800 transition-all hover:bg-gray-100">
                                    <span>{{ app()->getLocale() == 'ar' ? 'العربية' : (app()->getLocale() == 'nl' ? 'Nederlands' : 'English') }}</span>
                                    <svg class="h-3.5 w-3.5 text-gray-500 transition-transform duration-200"
                                        :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-cloak
                                    class="absolute right-0 mt-2 w-32 rounded-xl border border-gray-100 bg-white/95 backdrop-blur-md py-1 text-xs shadow-2xl z-50">
                                    <a wire:navigate href="{{ route('lang.switch', 'ar') }}"
                                        class="block px-3 py-2 text-gray-700 hover:bg-[#E07513]/10 hover:text-[#E07513]">العربية</a>
                                    <a wire:navigate href="{{ route('lang.switch', 'nl') }}"
                                        class="block px-3 py-2 text-gray-700 hover:bg-[#E07513]/10 hover:text-[#E07513]">Nederlands</a>
                                    <a wire:navigate href="{{ route('lang.switch', 'en') }}"
                                        class="block px-3 py-2 text-gray-700 hover:bg-[#E07513]/10 hover:text-[#E07513]">English</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- الصف الثاني: روابط التنقل (مع الخط النشط) --}}
                    <nav class="w-full lg:w-auto border-t border-gray-200/50 pt-2 lg:border-t-0 lg:pt-0"
                        aria-label="{{ __('messages.nav.primary') }}">
                        <ul
                            class="flex items-center justify-center gap-4 sm:gap-6 lg:gap-8 text-xs sm:text-sm font-bold text-gray-700">

                            <!-- رابط 1: الرئيسية -->
                            <li>
                                <a href="#home" @click="activeSection = 'home'"
                                    class="relative block pb-1 transition-colors duration-300 hover:text-[#E07513]"
                                    :class="activeSection === 'home' ? 'text-[#E07513]' : ''">
                                    {{ __('messages.nav.home') }}
                                    <!-- الخط السفلي للرابط النشط -->
                                    <span
                                        class="absolute bottom-0 left-0 h-0.5 bg-[#E07513] transition-all duration-300 ease-out"
                                        :class="activeSection === 'home' ? 'w-full' : 'w-0 hover:w-full'"></span>
                                </a>
                            </li>

                            <!-- رابط 2: المنيو -->
                            <li>
                                <a href="#menu" @click="activeSection = 'menu'"
                                    class="relative block pb-1 transition-colors duration-300 hover:text-[#E07513]"
                                    :class="activeSection === 'menu' ? 'text-[#E07513]' : ''">
                                    {{ __('messages.nav.menu') }}
                                    <span
                                        class="absolute bottom-0 left-0 h-0.5 bg-[#E07513] transition-all duration-300 ease-out"
                                        :class="activeSection === 'menu' ? 'w-full' : 'w-0 hover:w-full'"></span>
                                </a>
                            </li>

                            <!-- رابط 3: المعرض -->
                            <li>
                                <a href="#gallery" @click="activeSection = 'gallery'"
                                    class="relative block pb-1 transition-colors duration-300 hover:text-[#E07513]"
                                    :class="activeSection === 'gallery' ? 'text-[#E07513]' : ''">
                                    {{ __('messages.nav.gallery') }}
                                    <span
                                        class="absolute bottom-0 left-0 h-0.5 bg-[#E07513] transition-all duration-300 ease-out"
                                        :class="activeSection === 'gallery' ? 'w-full' : 'w-0 hover:w-full'"></span>
                                </a>
                            </li>

                            <!-- رابط 4: الحجز -->
                            <li>
                                <a href="#reservation" @click="activeSection = 'reservation'"
                                    class="relative block pb-1 transition-colors duration-300 hover:text-[#E07513]"
                                    :class="activeSection === 'reservation' ? 'text-[#E07513]' : ''">
                                    {{ __('messages.nav.reservation') }}
                                    <span
                                        class="absolute bottom-0 left-0 h-0.5 bg-[#E07513] transition-all duration-300 ease-out"
                                        :class="activeSection === 'reservation' ? 'w-full' : 'w-0 hover:w-full'"></span>
                                </a>
                            </li>
                        </ul>
                    </nav>

                    {{-- الجانب الأيمن لسطح المكتب فقط: أزرار التفاعل --}}
                    <div class="hidden lg:flex items-center gap-3 shrink-0">

                        <!-- زر الواتساب -->
                        <a href="https://wa.me/رقم_الهاتف_هنا" target="_blank"
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#25D366] text-white shadow-md shadow-[#25D366]/40 transition-all hover:scale-110 animate-pulse"
                            title="تواصل معنا">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" />
                            </svg>
                        </a>

                        <!-- زر Thuisbezorgd -->
                        <a href="https://www.thuisbezorgd.nl/menu/jemenitische-keuken-restaurant#pre" target="_blank"
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-gray-200 bg-white/50 p-2 shadow-sm transition-all hover:scale-105 hover:bg-gray-100">
                            <img src="{{ asset('images/thuisbezorgd.png') }}" alt="Thuisbezorgd"
                                class="h-full w-full object-contain" />
                        </a>

                        <!-- زر الحجز -->
                        <a href="#reservation"
                            class="inline-flex h-10 shrink-0 items-center justify-center whitespace-nowrap rounded-xl bg-gradient-to-r from-[#E07513] to-[#B85709] px-5 text-sm font-bold text-white shadow-md shadow-[#E07513]/25 transition-all hover:-translate-y-0.5 animate-pulse">
                            {{ __('messages.nav.bookTableBtn') }}
                        </a>

                        <!-- زر تبديل اللغة (كما هو) -->
                        <div class="relative shrink-0" x-data="{ open: false }">
                            <button @click="open = !open" @keydown.escape.window="open = false" type="button"
                                class="flex h-10 items-center gap-1.5 rounded-xl border border-gray-200 bg-white/50 px-3 text-xs font-bold text-gray-800 transition-all hover:bg-gray-100">
                                <span>{{ app()->getLocale() == 'ar' ? 'العربية' : (app()->getLocale() == 'nl' ? 'Nederlands' : 'English') }}</span>
                                <svg class="h-3.5 w-3.5 text-gray-500 transition-transform duration-200"
                                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak
                                class="absolute right-0 mt-2 w-32 rounded-xl border border-gray-100 bg-white/95 backdrop-blur-md py-1 text-xs shadow-2xl z-50">
                                <a wire:navigate href="{{ route('lang.switch', 'ar') }}"
                                    class="block px-3 py-2 text-gray-700 hover:bg-[#E07513]/10 hover:text-[#E07513]">العربية</a>
                                <a wire:navigate href="{{ route('lang.switch', 'nl') }}"
                                    class="block px-3 py-2 text-gray-700 hover:bg-[#E07513]/10 hover:text-[#E07513]">Nederlands</a>
                                <a wire:navigate href="{{ route('lang.switch', 'en') }}"
                                    class="block px-3 py-2 text-gray-700 hover:bg-[#E07513]/10 hover:text-[#E07513]">English</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </header>

    <!-- محتوى الصفحة الفردية (Single Page Sections) -->
    <main>
        {{ $slot }}
    </main>

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
    $whatsappHref = str_starts_with($whatsapp, 'http://') || str_starts_with($whatsapp, 'https://')
        ? $whatsapp
        : 'https://wa.me/' . preg_replace('/[^0-9]/', '', $whatsapp);

    // غيّر المسار إذا كان اسم صورة المدينة في مشروعك مختلفاً.
    $backgroundImage = asset('images/logo-transparent.webp');
@endphp

<footer dir="rtl" class="relative isolate min-h-[620px] overflow-hidden bg-[#171421] text-stone-100">
    {{-- خلفية المدينة والتدرج الداكن مثل الصورة المرجعية --}}
    <div class="pointer-events-none absolute inset-0 -z-20 bg-[#171421]"></div>
    <div class="pointer-events-none absolute inset-0 -z-10 bg-cover bg-center bg-no-repeat opacity-100  " style="background-image:url('{{ $backgroundImage }}')"></div>
    <div class="pointer-events-none absolute inset-0 -z-10 bg-[linear-gradient(180deg,rgba(15,18,37,.91)_0%,rgba(28,20,32,.80)_45%,rgba(31,12,5,.96)_100%)]"></div>

    <div class="mx-auto flex min-h-[620px] max-w-[1580px] flex-col px-8 pb-5 pt-8 sm:px-16 lg:px-[7.8%]">
        {{-- الشريط العلوي --}}
        <div class="flex items-start justify-between text-[11px] font-medium text-white/85">
            <span>{{ $translate('messages.footer.welcome', 'مرحبا بكم  ً') }}</span>
            <div class="text-center leading-tight">
                <div class="text-[17px] font-black text-white">{{ $settings?->localized_name ?: 'المطبخ اليمني' }}</div>
                <div class="mt-1 text-[9px] font-bold tracking-[.18em] text-[#d69045]">JEMENITISCHE KEUKEN</div>
            </div>
        </div>

        {{-- عنوان الصفحة --}}
        <div class="mt-3 text-center">
            <p class="text-[10px] font-bold tracking-[.32em] text-[#d89043]">THE ORIGIN OF MANDI</p>
            <h1 class="mt-1 text-[30px] font-black leading-tight text-white drop-shadow-lg sm:text-[34px]">نكهات يمنية أصيلة</h1>
            <p class="mt-1 text-[12px] font-medium text-white/85">تجربة يمنية دافئة تجمع المذاق الأصيل والضيافة الكريمة.</p>
            @if ($settings?->accepts_reservations)
                <a href="#reservation" class="mt-3 inline-flex h-9 items-center justify-center rounded-full bg-[#df791d] px-7 text-[13px] font-black text-white shadow-[0_4px_18px_rgba(223,121,29,.42)] transition hover:-translate-y-0.5 hover:bg-[#ef8b2b]">
                    {{ $translate('messages.footer.reserveNow', 'احجز طاولتك الآن') }}
                </a>
            @endif
        </div>

        {{-- ترتيب RTL: اكتشف يميناً، تواصل معنا في الوسط، ساعات العمل يساراً --}}
        <div class="mt-6 grid flex-1 grid-cols-1 gap-3 lg:grid-cols-3 lg:gap-5">
            {{-- اكتشف --}}
            <section class="min-h-[200px] max-h-60 rounded-[16px] border border-white/30 bg-[linear-gradient(135deg,rgba(79,57,59,.70),rgba(47,31,34,.76))] p-5 shadow-[0_12px_30px_rgba(0,0,0,.27)] backdrop-blur-[5px]">
                <h2 class="flex items-center gap-2 text-[15px] font-black text-white"><span class="h-2 w-2 rounded-full bg-[#e17a18] shadow-[0_0_10px_rgba(225,122,24,.85)]"></span>{{ $translate('messages.footer.quickLinks', 'اكتشف') }}</h2>
                <ul class="mt-5 space-y-2.5 text-[13px] font-medium leading-5 text-white/80">
                    <li><a href="#home" class="transition-colors hover:text-[#f1ae62]">{{ $translate('messages.nav.home', 'الرئيسية') }}</a></li>
                    <li><a href="#menu" class="transition-colors hover:text-[#f1ae62]">{{ $translate('messages.nav.menu', 'قائمة الطعام') }}</a></li>
                    <li><a href="#gallery" class="transition-colors hover:text-[#f1ae62]">{{ $translate('messages.nav.gallery', 'معرض الصور') }}</a></li>
                    @if ($settings?->accepts_reservations)
                        <li><a href="#reservation" class="font-bold text-[#f0a54f] transition-colors hover:text-[#ffd28d]">{{ $translate('messages.nav.reservation', 'الحجز') }}</a></li>
                    @endif
                </ul>
            </section>

            {{-- تواصل معنا --}}
            <section class="min-h-[200px] max-h-60 rounded-[16px] border border-white/30 bg-[linear-gradient(135deg,rgba(79,57,59,.70),rgba(47,31,34,.76))] p-5 shadow-[0_12px_30px_rgba(0,0,0,.27)] backdrop-blur-[5px]">
                <h2 class="flex items-center gap-2 text-[15px] font-black text-white"><span class="h-2 w-2 rounded-full bg-[#e17a18] shadow-[0_0_10px_rgba(225,122,24,.85)]"></span>{{ $translate('messages.footer.contact', 'تواصل معنا') }}</h2>
                <div class="mt-4 space-y-2 text-[13px] font-medium leading-5 text-white/85">
                    @if ($address || $city || $postalCode)
                        <p class="flex items-center gap-2"><x-lucide-map-pin class="h-4 w-4 shrink-0 text-[#efa452]" /> <span>{{ $address }}@if ($city), {{ $city }}@endif @if ($postalCode), {{ $postalCode }}@endif</span></p>
                    @endif
                    @if ($phone && $phoneHref)
                        <a dir="ltr" href="tel:{{ $phoneHref }}" class="block w-fit transition-colors hover:text-[#f1ae62]">{{ $phone }}</a>
                    @endif
                    @if ($email)
                        <a dir="ltr" href="mailto:{{ $email }}" class="block w-fit transition-colors hover:text-[#f1ae62]">{{ $email }}</a>
                    @endif
                </div>

                <div class="mt-4 flex items-center justify-center gap-2">
                    <a href="#" aria-label="TikTok" class="flex h-11 w-11 items-center justify-center rounded-xl border border-cyan-200 bg-[#10131d] text-white shadow-[0_0_12px_rgba(84,231,224,.22)] transition hover:-translate-y-0.5"><x-lucide-music-2 class="h-5 w-5" /></a>
                    @if ($whatsapp && $whatsappHref !== 'https://wa.me/')
                        <a href="{{ $whatsappHref }}" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp" class="flex h-11 w-11 items-center justify-center rounded-xl border border-emerald-200 bg-[#079b67] text-white transition hover:-translate-y-0.5 hover:bg-[#12b77d]">  <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" />
                            </svg></a>
                    @endif
                </div>

                @if ($mapsLink)
                    <a href="{{ $mapsLink }}" target="_blank" rel="noopener noreferrer" class="mx-auto mt-3 flex h-9 w-fit items-center gap-1.5 rounded-xl border border-[#d89145] bg-[#804315]/60 px-3 text-[12px] font-bold text-[#f0b66d] transition hover:bg-[#a85a1d]/70"><x-lucide-map-pin class="h-4 w-4" />{{ $translate('messages.footer.viewMap', 'الموقع على خريطة جوجل') }}</a>
                @endif
            </section>

            {{-- ساعات العمل --}}
            <section class="min-h-[200px] max-h-60 rounded-[16px] border border-white/30 bg-[linear-gradient(135deg,rgba(79,57,59,.70),rgba(47,31,34,.76))] p-5 shadow-[0_12px_30px_rgba(0,0,0,.27)] backdrop-blur-[5px]">
                <h2 class="flex items-center gap-2 text-[15px] font-black text-white"><span class="h-2 w-2 rounded-full bg-[#e17a18] shadow-[0_0_10px_rgba(225,122,24,.85)]"></span>{{ $translate('messages.footer.hours', 'ساعات العمل') }}</h2>
                @if ($openingHours)
                    <dl class="mt-5 space-y-1.5 text-[12px] font-medium">
                        @foreach ($openingHours as $day => $hours)
                            @php
                                $hoursText = is_array($hours) ? implode(' - ', array_filter(array_map(static fn ($value): string => (string) $value, $hours))) : (string) $hours;
                            @endphp
                            <div class="flex items-center justify-between gap-4">
                                <dt class="text-white/80">{{ $translate('messages.days.' . strtolower((string) $day), (string) $day) }}</dt>
                                <dd dir="ltr" class="font-bold text-[#efa452]">{{ $hoursText }}</dd>
                            </div>
                        @endforeach
                    </dl>
                @else
                    <p class="mt-5 text-[13px] leading-6 text-white/70">{{ $translate('messages.footer.hoursUnavailable', 'يرجى التواصل معنا لمعرفة ساعات العمل.') }}</p>
                @endif
            </section>
        </div>

        <div class="mt-5 flex items-center justify-between border-t border-[#c57832]/70 pt-3 text-[10px] font-medium text-white/65">
            <p>{{ $translate('messages.footer.rights', 'جميع الحقوق محفوظة') }} <span dir="ltr">© {{ now()->year }}</span></p>
            <p class="text-[#d69348]">The Origin Of Mandi • أصل المندي</p>
        </div>
    </div>
</footer>

{{-- If Lucide is loaded through Vite, this line activates every data-lucide icon above. --}}
@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (window.lucide) window.lucide.createIcons();
            });
        </script>
    @endpush
@endonce



    <!-- زر العودة للأعلى (Scroll to Top) -->
    <button x-data="{ show: false }" @scroll.window="show = window.pageYOffset > 300"
        @click="window.scrollTo({top: 0, behavior: 'smooth'})" x-show="show"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8"
        x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-8"
        class="fixed bottom-6 {{ app()->getLocale() == 'ar' ? 'left-6' : 'right-6' }} z-[60] flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-t from-[#E07513] to-[#B85709] text-white shadow-xl shadow-[#E07513]/30 transition-all hover:-translate-y-1 hover:shadow-2xl hover:shadow-[#E07513]/40 focus:outline-none focus:ring-2 focus:ring-[#FFD700]"
        aria-label="{{ __('messages.accessibility.scrollToTop') ?? 'العودة للأعلى' }}" x-cloak>
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="m18 15-6-6-6 6" />
        </svg>
    </button>

    <!-- ========================
         نظام الإشعارات الأنيق (Toast Notifications)
         يستمع لأحداث Livewire ويعرض إشعاراً عند النجاح
    ======================== -->
    <div x-data="{
        toasts: [],
        add(toast) {
            const id = Date.now();
            this.toasts.push({ id, ...toast, visible: false });
            this.$nextTick(() => {
                const t = this.toasts.find(t => t.id === id);
                if (t) t.visible = true;
            });
            setTimeout(() => this.remove(id), toast.duration ?? 6000);
        },
        remove(id) {
            const t = this.toasts.find(t => t.id === id);
            if (t) {
                t.visible = false;
                setTimeout(() => {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }, 500);
            }
        }
    }" @notify.window="add($event.detail)"
        class="fixed bottom-6 {{ app()->getLocale() === 'ar' ? 'right-6' : 'left-6' }} z-[999] flex flex-col gap-3 pointer-events-none"
        aria-live="polite" aria-atomic="true">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.visible" x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-y-6 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-400"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                class="pointer-events-auto flex items-start gap-3 min-w-[280px] max-w-sm w-full rounded-2xl border border-[#E07513]/30 bg-[#1C0705]/95 px-4 py-3.5 shadow-2xl shadow-black/50 backdrop-blur-md ring-1 ring-white/5"
                role="alert">
                {{-- أيقونة النجاح --}}
                <div
                    class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-500/15 border border-emerald-500/30">
                    <svg class="h-5 w-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                {{-- المحتوى --}}
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-black text-white leading-snug" x-text="toast.title"></p>
                    <p class="mt-0.5 text-xs text-stone-400 leading-relaxed" x-text="toast.message"
                        x-show="toast.message"></p>

                    {{-- شريط التقدم (Progress Bar) يختفي مع الوقت --}}
                    <div class="mt-2 h-0.5 w-full rounded-full bg-white/10 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-[#E07513]"
                            x-bind:style="`animation: shrink ${toast.duration ?? 6000}ms linear forwards`"></div>
                    </div>
                </div>

                {{-- زر الإغلاق --}}
                <button @click="remove(toast.id)"
                    class="mt-0.5 shrink-0 text-stone-500 hover:text-stone-300 transition-colors" aria-label="Close">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </template>
    </div>

    {{-- CSS: حركة شريط التقدم --}}
    <style>
        @keyframes shrink {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }
    </style>



    @livewireScripts
</body>

</html>
