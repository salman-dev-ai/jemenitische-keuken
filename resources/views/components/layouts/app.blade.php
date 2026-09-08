@props(['settings' => null])


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
    class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? __('messages.brand.name') . ' - ' . __('messages.brand.slogan') }}</title>

    <!-- الخطوط العربي واللاتينية الفاخرة -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Tajawal:wght@400;500;700;800;900&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-['Tajawal'] bg-[#FDFBF7] text-[#2C1810] antialiased selection:bg-[#E07513] selection:text-white">

    <!-- شريط   الرئيسي الفاخر (الشعار في المنتصف) -->
    <header x-data="{
        mobileOpen: false,
        languageOpen: false,
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
        class="sticky top-0 z-50 w-full overflow-x-clip border-b border-[#E07513]/20 bg-[#250B08]/90 text-white shadow-xl backdrop-blur-md">
        <div class="mx-auto max-w-7xl px-3 sm:px-6 lg:px-8">
            <div
                class="grid min-h-20 grid-cols-[minmax(0,1fr)_auto_minmax(0,1fr)] items-center gap-x-2 py-2 sm:min-h-24 sm:gap-x-4 sm:py-3 lg:gap-x-6 lg:py-0">
                {{-- الجانب الأول: روابط سطح المكتب + اللغة --}}
                <div class="flex min-w-0 items-center justify-start gap-2 sm:gap-3 lg:gap-6">
                    <nav class="hidden items-center gap-5 text-sm font-bold text-stone-200 lg:flex xl:gap-6"
                        aria-label="{{ __('messages.nav.primary') }}">
                        <a href="#home"
                            class="whitespace-nowrap transition-colors hover:text-[#E07513]">{{ __('messages.nav.home') }}</a>
                    </nav>

                    <!-- زر تبديل اللغات (العربية / English / Nederlands) -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" type="button"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-black/40 hover:bg-black/60 border border-white/15 text-xs font-bold text-white transition-all">
                            <span>{{ app()->getLocale() == 'ar' ? '🇾🇪 العربية' : (app()->getLocale() == 'nl' ? '🇳🇱 Nederlands' : '🇬🇧 English') }}</span>
                            <svg class="w-3.5 h-3.5 text-stone-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak
                            class="absolute  mt-2 w-40 rounded-2xl bg-[#260C0A] border border-white/15 shadow-2xl py-1 z-50 text-xs">
                            <a href="{{ route('lang.switch', 'ar') }}"
                                class="flex items-center gap-2 px-3 py-2 text-stone-200 hover:bg-[#E07513]/20  rounded-2xl  rou hover:text-[#E07513] transition-colors">
                                <span>🇾🇪</span> <span>العربية</span>
                            </a>
                            <a href="{{ route('lang.switch', 'nl') }}"
                                class="flex items-center gap-2 px-3 py-2 text-stone-200 hover:bg-[#E07513]/20  rounded-2xl hover:text-[#E07513] transition-colors">
                                <span>🇳🇱</span> <span>Nederlands</span>
                            </a>
                            <a href="{{ route('lang.switch', 'en') }}"
                                class="flex items-center gap-2 px-3 py-2 text-stone-200 hover:bg-[#E07513]/20  rounded-2xl hover:text-[#E07513] transition-colors">
                                <span>🇬🇧</span> <span>English</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- الشعار: العمود الأوسط يبقى ثابتاً في كل المقاسات --}}
                <a href="#home"
                    class="group flex min-w-0 max-w-[6.5rem] flex-col items-center text-center sm:max-w-[9rem] lg:max-w-none"
                    aria-label="المطبخ اليمني">
                    <img src="{{ asset('images/logo.jpg') }}" alt="المطبخ اليمني - Jemenitische Keuken" width="200"
                        height="200"
                        class="!h-10 w-auto max-w-full object-contain transition-transform group-hover:scale-105 sm:h-12  rounded-br-lg  rounded-tl-lg">
                    {{-- <span class="mt-1 max-w-full truncate text-xs font-extrabold leading-none tracking-wide text-[#E07513] sm:text-base">المطبخ اليمني</span>
                <span class="hidden max-w-full truncate font-['Plus_Jakarta_Sans'] text-[9px] font-bold uppercase tracking-widest text-white sm:block">JEMENITISCHE KEUKEN</span> --}}
                </a>

                {{-- الجانب الثاني: روابط سطح المكتب + الحجز + زر قائمة الجوال --}}
                <div class="flex min-w-0 items-center justify-end gap-2 sm:gap-3 lg:gap-6">
                    <nav class="hidden items-center gap-5 text-sm font-bold text-stone-200 lg:flex xl:gap-6"
                        aria-label="{{ __('messages.nav.secondary') }}">
                        <a href="#gallery"
                            class="whitespace-nowrap transition-colors hover:text-[#E07513]">{{ __('messages.nav.gallery') }}</a>
                        <a href="#menu"
                            class="whitespace-nowrap transition-colors hover:text-[#E07513]">{{ __('messages.nav.menu') }}</a>

                        {{-- <a href="#contact" class="whitespace-nowrap transition-colors hover:text-[#E07513]">{{ __('messages.nav.contact') }}</a> --}}
                    </nav>

                    {{-- <a href="https://www.thuisbezorgd.nl/menu/jemenitische-keuken-restaurant#pre" target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex min-h-10 shrink-0 items-center justify-center gap-1.5 whitespace-nowrap rounded-xl border border-[#E07513]/50 bg-black/40 px-2.5 py-2 text-[10px] font-bold text-[#E07513] shadow-sm transition-all hover:-translate-y-0.5 hover:bg-[#E07513]/10 hover:text-[#F08A25] hover:border-[#E07513] focus:outline-none focus:ring-2 focus:ring-[#FFD700] sm:px-4 sm:text-sm"
                        title="Bestel via Thuisbezorgd.nl">
                        <svg {{ $attributes }} xmlns="http://w3.org" viewBox="0 0 78 75" fill="none">
                            <!-- شكل البيت البرتقالي الخلفي -->
                            <path
                                d="M39 0C25.4 0 0 13.9 0 28.5V67.3C0 71.5 3.4 74.9 7.6 74.9H70.4C74.6 74.9 78 71.5 78 67.3V28.5C78 13.9 52.6 0 39 0Z"
                                fill="#FF8000" />

                            <!-- السكين (أبيض بالداخل) -->
                            <path
                                d="M46.7 20.3C45.2 20.3 44 21.5 44 23V42C44 43.1 44.7 44 45.7 44.3V57.6C45.7 58.7 46.6 59.6 47.7 59.6C48.8 59.6 49.7 58.7 49.7 57.6V44.3C50.7 44 51.4 43.1 51.4 42V25C51.4 22.4 49.3 20.3 46.7 20.3Z"
                                fill="white" />

                            <!-- الشوكة (أبيض بالداخل) -->
                            <path
                                d="M31.2 20.3C28.6 20.3 26.5 22.4 26.5 25V33.9C26.5 35 27.2 35.9 28.2 36.2V57.6C28.2 58.7 29.1 59.6 30.2 59.6C31.3 59.6 32.2 58.7 32.2 57.6V36.2C33.2 35.9 33.9 35 33.9 33.9V25C33.9 23.9 33 23 31.9 23C30.8 23 29.9 23.9 29.9 25V31.5H29.1V25C29.1 23.9 28.2 23 27.1 23C26 23 25.1 23.9 25.1 25V33.9C25.1 36.3 26.8 38.3 29.1 38.8V57.6C29.1 58.7 30 59.6 31.1 59.6C32.2 59.6 33.1 58.7 33.1 57.6V38.8C35.4 38.3 37.1 36.3 37.1 33.9V25C37.1 22.4 35 20.3 32.4 20.3H31.2Z"
                                fill="white" />
                        </svg>
                    </a> --}}

                    <a href="https://www.thuisbezorgd.nl/menu/jemenitische-keuken-restaurant#pre" target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex min-h-10 shrink-0 items-center justify-center gap-1.5 whitespace-nowrap rounded-xl border border-[#E07513]/50 bg-black/40 px-2.5 py-2 text-[10px] font-bold text-[#E07513] shadow-sm transition-all hover:-translate-y-0.5 hover:bg-[#E07513]/10 hover:text-[#F08A25] hover:border-[#E07513] focus:outline-none focus:ring-2 focus:ring-[#FFD700] sm:px-4 sm:text-sm"
                        title="Bestel via Thuisbezorgd.nl">

                        <!-- الأيقونة الرسمية بعد ضبط المقاسات والرابط القياسي -->
                        <svg xmlns="http://w3.org" viewBox="0 0 78 75" fill="none"
                            class="w-5 h-5 sm:w-6 sm:h-6 shrink-0">
                            <!-- شكل البيت البرتقالي الخلفي -->
                            <path
                                d="M39 0C25.4 0 0 13.9 0 28.5V67.3C0 71.5 3.4 74.9 7.6 74.9H70.4C74.6 74.9 78 71.5 78 67.3V28.5C78 13.9 52.6 0 39 0Z"
                                fill="#FF8000" />

                            <!-- السكين (أبيض بالداخل) -->
                            <path
                                d="M46.7 20.3C45.2 20.3 44 21.5 44 23V42C44 43.1 44.7 44 45.7 44.3V57.6C45.7 58.7 46.6 59.6 47.7 59.6C48.8 59.6 49.7 58.7 49.7 57.6V44.3C50.7 44 51.4 43.1 51.4 42V25C51.4 22.4 49.3 20.3 46.7 20.3Z"
                                fill="white" />

                            <!-- الشوكة (أبيض بالداخل) -->
                            <path
                                d="M31.2 20.3C28.6 20.3 26.5 22.4 26.5 25V33.9C26.5 35 27.2 35.9 28.2 36.2V57.6C28.2 58.7 29.1 59.6 30.2 59.6C31.3 59.6 32.2 58.7 32.2 57.6V36.2C33.2 35.9 33.9 35 33.9 33.9V25C33.9 23.9 33 23 31.9 23C30.8 23 29.9 23.9 29.9 25V31.5H29.1V25C29.1 23.9 28.2 23 27.1 23C26 23 25.1 23.9 25.1 25V33.9C25.1 36.3 26.8 38.3 29.1 38.8V57.6C29.1 58.7 30 59.6 31.1 59.6C32.2 59.6 33.1 58.7 33.1 57.6V38.8C35.4 38.3 37.1 36.3 37.1 33.9V25C37.1 22.4 35 20.3 32.4 20.3H31.2Z"
                                fill="white" />
                        </svg>

                        <span>Thuisbezorgd.nl</span>
                    </a>

                    <a href="#reservation"
                        class="inline-flex min-h-10 shrink-0 items-center justify-center whitespace-nowrap rounded-xl bg-gradient-to-r from-[#E07513] to-[#B85709] px-2.5 py-2 text-[10px] font-bold text-white shadow-lg shadow-[#E07513]/25 transition-all hover:-translate-y-0.5 hover:from-[#c2620a] hover:to-[#9a4504] hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-[#FFD700] sm:px-5 sm:text-sm">
                        {{ __('messages.nav.bookTableBtn') }}
                    </a>

                    <button type="button" @click="toggleMobile()" :aria-expanded="mobileOpen"
                        aria-controls="mobile-navigation"
                        class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-white/15 bg-black/30 text-white transition-colors hover:bg-[#E07513]/20 focus:outline-none focus:ring-2 focus:ring-[#FFD700] lg:hidden"
                        aria-label="{{ __('messages.nav.toggleMenu') }}">
                        <svg class="hidden h-5 w-5" :class="{ 'block': !mobileOpen, 'hidden': mobileOpen }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="hidden h-5 w-5" :class="{ 'block': mobileOpen, 'hidden': !mobileOpen }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 6l12 12M18 6 6 18" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- قائمة الجوال: تظهر حتى lg ثم تختفي تلقائياً على سطح المكتب --}}
            <div id="mobile-navigation" class="hidden border-t border-[#E07513]/20 pb-3 pt-2 lg:hidden"
                :class="{ 'block': mobileOpen, 'hidden': !mobileOpen }">
                <nav class="grid grid-cols-1 gap-1 sm:grid-cols-2" aria-label="{{ __('messages.nav.mobile') }}">
                    <a href="#home" @click="closeMenus()"
                        class="flex min-h-11 items-center rounded-xl px-3 py-2 text-sm font-bold text-stone-200 transition-colors hover:bg-[#E07513]/20 hover:text-[#E07513]">{{ __('messages.nav.home') }}</a>
                    <a href="#menu" @click="closeMenus()"
                        class="flex min-h-11 items-center rounded-xl px-3 py-2 text-sm font-bold text-stone-200 transition-colors hover:bg-[#E07513]/20 hover:text-[#E07513]">{{ __('messages.nav.menu') }}</a>
                    <a href="#gallery" @click="closeMenus()"
                        class="flex min-h-11 items-center rounded-xl px-3 py-2 text-sm font-bold text-stone-200 transition-colors hover:bg-[#E07513]/20 hover:text-[#E07513]">{{ __('messages.nav.gallery') }}</a>
                    <a href="#reservation" @click="closeMenus()"
                        class="flex min-h-11 items-center rounded-xl px-3 py-2 text-sm font-bold text-stone-200 transition-colors hover:bg-[#E07513]/20 hover:text-[#E07513]">{{ __('messages.nav.reservation') }}</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- محتوى الصفحة الفردية (Single Page Sections) -->
    <main>
        {{ $slot }}
    </main>

    <!-- الذيل (Footer) مع الشعار كخلفية مائية (Watermark) -->

    @php
        $translate = static function (string $key, string $fallback): string {
            return \Illuminate\Support\Facades\Lang::has($key) ? __("$key") : $fallback;
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
    @endphp

    <footer class="relative isolate overflow-hidden border-t border-[#E07513]/30 bg-[#160504] text-stone-300">
        {{-- Decorative background using Tailwind utilities only. --}}
        <div
            class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-64 bg-linear-to-b from-[#E07513]/10 via-[#7A2D0A]/5 to-transparent">
        </div>
        <div
            class="pointer-events-none absolute -bottom-32 start-1/2 -z-10 hidden h-96 w-96 -translate-x-1/2 rounded-full bg-[#E07513]/10 blur-3xl sm:block">
        </div>

        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-16 lg:px-8 lg:py-20">
            <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-[1.35fr_0.8fr_1fr_1fr] lg:gap-12">
                {{-- Brand --}}
                <div class="sm:col-span-2 lg:col-span-1">
                    <a href="#home" class="group inline-flex items-center gap-3"
                        aria-label="{{ $settings?->localized_name ?: 'المطبخ اليمني' }}">
                        <span
                            class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-[#E07513]/40 bg-[#250B08] p-1.5 shadow-lg shadow-black/20 transition-transform duration-300 group-hover:-translate-y-1">
                            <img src="{{ asset('images/logo.jpg') }}" alt="" width="96" height="96"
                                class="h-full w-full rounded-xl object-cover">
                        </span>
                        <span class="min-w-0">
                            <span class="block truncate text-base font-black tracking-wide text-white sm:text-lg">
                                {{ $settings?->localized_name ?: 'المطبخ اليمني' }}
                            </span>
                            <span
                                class="mt-1 block truncate font-['Plus_Jakarta_Sans'] text-[9px] font-bold uppercase tracking-[0.22em] text-[#E07513]">
                                JEMENITISCHE KEUKEN
                            </span>
                        </span>
                    </a>

                    <p class="mt-6 max-w-sm text-sm leading-7 text-stone-400">
                        {{ __('messages.brand.description') ?: 'نكهات يمنية أصيلة، وضيافة تترك أثراً لا يُنسى.' }}
                    </p>

                    <div
                        class="mt-6 inline-flex items-center gap-2 rounded-full border border-[#E07513]/25 bg-[#E07513]/10 px-3 py-2 text-xs font-bold text-amber-200">
                        <span class="h-2 w-2 rounded-full bg-emerald-400 shadow-[0_0_12px_rgba(52,211,153,0.8)]"
                            aria-hidden="true"></span>
                        <span>{{ __('messages.footer.welcome') }}</span>
                    </div>
                </div>

                {{-- Quick links --}}
                <div>
                    <h3 class="flex items-center gap-3 text-sm font-black tracking-wide text-white">
                        <span class="h-2 w-2 rounded-full bg-[#E07513] shadow-[0_0_12px_rgba(224,117,19,0.8)]"
                            aria-hidden="true"></span>
                        {{ __('messages.footer.quickLinks') }}
                    </h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        <li><a href="#home"
                                class="inline-flex min-h-8 items-center transition-colors hover:text-[#E07513] focus:text-[#E07513] focus:outline-none">{{ __('messages.nav.home') }}</a>
                        </li>
                        <li><a href="#menu"
                                class="inline-flex min-h-8 items-center transition-colors hover:text-[#E07513] focus:text-[#E07513] focus:outline-none">{{ __('messages.nav.menu') }}</a>
                        </li>
                        <li><a href="#gallery"
                                class="inline-flex min-h-8 items-center transition-colors hover:text-[#E07513] focus:text-[#E07513] focus:outline-none">{{ __('messages.nav.gallery') }}</a>
                        </li>
                        <li><a href="#reservation"
                                class="inline-flex min-h-8 items-center font-bold text-amber-300 transition-colors hover:text-[#FFD700] focus:text-[#FFD700] focus:outline-none">{{ __('messages.nav.reservation') }}</a>
                        </li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div>
                    <h3 class="flex items-center gap-3 text-sm font-black tracking-wide text-white">
                        <span class="h-2 w-2 rounded-full bg-[#E07513] shadow-[0_0_12px_rgba(224,117,19,0.8)]"
                            aria-hidden="true"></span>
                        {{ $translate('messages.footer.contact', 'تواصل معنا') }}
                    </h3>

                    <ul class="mt-5 space-y-4 text-sm">
                        @if ($address || $city || $postalCode)
                            <li class="flex items-start gap-3">
                                <span
                                    class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-[#E07513]/25 bg-[#E07513]/10 text-[#E07513]"
                                    aria-hidden="true">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M12 21s7-5.4 7-11a7 7 0 1 0-14 0c0 5.6 7 11 7 11Z" />
                                        <circle cx="12" cy="10" r="2.3" stroke-width="1.8" />
                                    </svg>
                                </span>
                                <span class="min-w-0 leading-6 text-stone-400">
                                    {{ $address }}@if ($city)
                                        , {{ $city }}
                                        @endif @if ($postalCode)
                                            , {{ $postalCode }}
                                        @endif
                                </span>
                            </li>
                        @endif

                        @if ($phone && $phoneHref)
                            <li>
                                <a href="tel:{{ $phoneHref }}"
                                    class="group flex items-center gap-3 transition-colors hover:text-[#E07513] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#FFD700]">
                                    <span
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-[#E07513]/25 bg-[#E07513]/10 text-[#E07513]"
                                        aria-hidden="true">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M5 4h3l2 5-2 1.5a13 13 0 0 0 5.5 5.5L15 14l5 2v3a2 2 0 0 1-2 2C10.3 21 3 13.7 3 5a2 2 0 0 1 2-1Z" />
                                        </svg>
                                    </span>
                                    <span dir="ltr"
                                        class="truncate text-stone-400 group-hover:text-[#E07513]">{{ $phone }}</span>
                                </a>
                            </li>
                        @endif

                        @if ($email)
                            <li>
                                <a href="mailto:{{ $email }}"
                                    class="group flex min-w-0 items-center gap-3 transition-colors hover:text-[#E07513] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#FFD700]">
                                    <span
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-[#E07513]/25 bg-[#E07513]/10 text-[#E07513]"
                                        aria-hidden="true">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="m3 6 9 6 9-6M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z" />
                                        </svg>
                                    </span>
                                    <span dir="ltr"
                                        class="truncate text-stone-400 group-hover:text-[#E07513]">{{ $email }}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>

                {{-- Hours and action --}}
                <div>
                    <h3 class="flex items-center gap-3 text-sm font-black tracking-wide text-white">
                        <span class="h-2 w-2 rounded-full bg-[#E07513] shadow-[0_0_12px_rgba(224,117,19,0.8)]"
                            aria-hidden="true"></span>
                        {{ $translate('messages.footer.hours', 'ساعات العمل') }}
                    </h3>

                    @if ($openingHours)
                        <dl class="mt-5 max-h-44 space-y-2 overflow-y-auto pe-2 text-xs">
                            @foreach ($openingHours as $day => $hours)
                                @php
                                    $hoursText = is_array($hours)
                                        ? implode(
                                            ' - ',
                                            array_filter(
                                                array_map(static fn($value): string => (string) $value, $hours),
                                            ),
                                        )
                                        : (string) $hours;
                                @endphp
                                <div
                                    class="flex items-center justify-between gap-3 border-b border-white/5 pb-2 last:border-0">
                                    <dt class="font-bold text-stone-400">
                                        {{ $translate('messages.days.' . strtolower((string) $day), (string) $day) }}
                                    </dt>
                                    <dd dir="ltr" class="text-end font-semibold text-stone-200">
                                        {{ $hoursText }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    @else
                        <p class="mt-5 text-sm leading-6 text-stone-400">
                            {{ $translate('messages.footer.hoursUnavailable', 'يرجى التواصل معنا لمعرفة ساعات العمل.') }}
                        </p>
                    @endif

                    @if ($settings?->accepts_reservations)
                        <a href="#reservation"
                            class="mt-6 inline-flex min-h-11 w-full items-center justify-center rounded-xl bg-linear-to-r from-[#E07513] to-[#B85709] px-4 py-3 text-center text-sm font-black text-white shadow-lg shadow-[#E07513]/20 transition-all hover:-translate-y-0.5 hover:from-[#F08A25] hover:to-[#C8660E] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#FFD700]">
                            {{ $translate('messages.footer.reserveNow', 'احجز طاولتك الآن') }}
                        </a>
                    @endif

                    @if ($whatsapp && $whatsappHref !== 'https://wa.me/')
                        <a href="{{ $whatsappHref }}" target="_blank" rel="noopener noreferrer"
                            class="mt-3 inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-center text-sm font-bold text-emerald-300 transition-colors hover:bg-emerald-500/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-300">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path
                                    d="M20.5 3.5A11.8 11.8 0 0 0 12.1 0C5.5 0 .2 5.3.2 11.9c0 2.1.6 4.1 1.6 5.9L.1 24l6.3-1.7a11.8 11.8 0 0 0 5.7 1.5h.1c6.5 0 11.8-5.3 11.8-11.9 0-3.2-1.3-6.2-3.5-8.4Zm-8.4 18.3h-.1a9.8 9.8 0 0 1-5-1.4l-.4-.2-3.7 1 1-3.6-.2-.4a9.8 9.8 0 0 1-1.5-5.3c0-5.4 4.4-9.8 9.8-9.8 2.6 0 5.1 1 7 2.9 1.9 1.9 2.9 4.3 2.9 7 0 5.4-4.4 9.8-9.8 9.8Zm5.4-7.3c-.3-.2-1.7-.8-2-.9-.3-.1-.5-.2-.7.2-.2.3-.8.9-.9 1.1-.2.2-.3.2-.6.1-1.7-.8-2.8-1.4-3.9-3.2-.3-.5.3-.5.8-1.6.1-.2.1-.4 0-.6-.1-.2-.7-1.7-.9-2.3-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.6.1-.9.4-.3.3-1.2 1.2-1.2 2.8s1.2 3.3 1.3 3.5c.2.2 2.3 3.5 5.6 4.9.8.3 1.4.5 1.9.6.8.3 1.5.2 2 .1.6-.1 1.7-.7 1.9-1.3.2-.6.2-1.2.1-1.3-.1-.1-.3-.2-.6-.3Z" />
                            </svg>
                            {{ $translate('messages.footer.whatsapp', 'تواصل عبر WhatsApp') }}
                        </a>
                    @endif
                </div>
            </div>

            <div class="my-10 h-px bg-linear-to-r from-transparent via-[#E07513]/35 to-transparent"></div>

            <div
                class="flex flex-col gap-5 text-center text-xs text-stone-500 sm:flex-row sm:items-center sm:justify-between sm:text-start">
                <p>
                    {{ $translate('messages.footer.rights', 'جميع الحقوق محفوظة') }}
                    <span dir="ltr">© {{ now()->year }}</span>
                </p>
                @if ($mapsLink)
                    <a href="{{ $mapsLink }}" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center justify-center gap-2 font-bold text-[#E07513] transition-colors hover:text-[#FFD700] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#FFD700] sm:justify-start">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M12 21s7-5.4 7-11a7 7 0 1 0-14 0c0 5.6 7 11 7 11Z" />
                            <circle cx="12" cy="10" r="2.3" stroke-width="1.8" />
                        </svg>
                        {{ $translate('messages.footer.viewMap', 'عرض الموقع على الخريطة') }}
                    </a>
                @else
                    <p class="font-bold text-[#E07513]">The Origin Of Mandi • أصل المندي</p>
                @endif
            </div>
        </div>

        {{-- Watermark kept decorative and hidden on narrow screens to avoid visual noise. --}}
        <img src="{{ asset('images/logo.jpg') }}" alt="" aria-hidden="true" width="550" height="550"
            class="pointer-events-none absolute -bottom-24 start-1/2 -z-10 block w-[24rem] max-w-none -translate-x-1/2 select-none opacity-[0.035] grayscale invert sm:w-[28rem] lg:w-[34rem]">
    </footer>


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
    <div
        x-data="{
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
        }"
        @notify.window="add($event.detail)"
        class="fixed bottom-6 {{ app()->getLocale() === 'ar' ? 'right-6' : 'left-6' }} z-[999] flex flex-col gap-3 pointer-events-none"
        aria-live="polite"
        aria-atomic="true"
    >
        <template x-for="toast in toasts" :key="toast.id">
            <div
                x-show="toast.visible"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-y-6 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-400"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                class="pointer-events-auto flex items-start gap-3 min-w-[280px] max-w-sm w-full rounded-2xl border border-[#E07513]/30 bg-[#1C0705]/95 px-4 py-3.5 shadow-2xl shadow-black/50 backdrop-blur-md ring-1 ring-white/5"
                role="alert"
            >
                {{-- أيقونة النجاح --}}
                <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-500/15 border border-emerald-500/30">
                    <svg class="h-5 w-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>

                {{-- المحتوى --}}
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-black text-white leading-snug" x-text="toast.title"></p>
                    <p class="mt-0.5 text-xs text-stone-400 leading-relaxed" x-text="toast.message" x-show="toast.message"></p>

                    {{-- شريط التقدم (Progress Bar) يختفي مع الوقت --}}
                    <div class="mt-2 h-0.5 w-full rounded-full bg-white/10 overflow-hidden">
                        <div
                            class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-[#E07513]"
                            x-bind:style="`animation: shrink ${toast.duration ?? 6000}ms linear forwards`"
                        ></div>
                    </div>
                </div>

                {{-- زر الإغلاق --}}
                <button
                    @click="remove(toast.id)"
                    class="mt-0.5 shrink-0 text-stone-500 hover:text-stone-300 transition-colors"
                    aria-label="Close"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </template>
    </div>

    {{-- CSS: حركة شريط التقدم --}}
    <style>
        @keyframes shrink {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>

    @livewireScripts
</body>

</html>
