<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? __('messages.brand.name') . ' - ' . __('messages.brand.slogan') }}</title>

    <!-- الخطوط العربي واللاتينية الفاخرة -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-['Tajawal'] bg-[#FDFBF7] text-[#2C1810] antialiased selection:bg-[#E07513] selection:text-white">

    <!-- شريط الملاحة الرئيسي الفاخر (الشعار في المنتصف) -->
    <header class="sticky top-0 z-50 bg-[#250B08]/95 backdrop-blur-md border-b border-[#E07513]/20 text-white shadow-xl transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-24 flex items-center justify-between gap-4">

            <!-- المنطقة الأولى (اليسار): 3 أزرار تنقل + زر تبديل اللغة -->
            <div class="flex items-center gap-4 lg:gap-6 flex-1 justify-start">
                <nav class="hidden lg:flex items-center gap-6 font-bold text-sm text-stone-200">
                    <a href="#home" class="hover:text-[#E07513] transition-colors">{{ __('messages.nav.home') }}</a>
                    <a href="#about" class="hover:text-[#E07513] transition-colors">{{ __('messages.nav.about') }}</a>
                    <a href="#menu" class="hover:text-[#E07513] transition-colors">{{ __('messages.nav.menu') }}</a>
                </nav>

                <!-- زر تبديل اللغات (العربية / English / Nederlands) -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" type="button" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-black/40 hover:bg-black/60 border border-white/15 text-xs font-bold text-white transition-all">
                        <span>{{ app()->getLocale() == 'ar' ? '🇾🇪 العربية' : (app()->getLocale() == 'nl' ? '🇳🇱 Nederlands' : '🇬🇧 English') }}</span>
                        <svg class="w-3.5 h-3.5 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-40 rounded-2xl bg-[#260C0A] border border-white/15 shadow-2xl py-1 z-50 text-xs">
                        <a href="{{ route('lang.switch', 'ar') }}" class="flex items-center gap-2 px-3 py-2 text-stone-200 hover:bg-[#E07513]/20 hover:text-[#E07513] transition-colors" wire:navigate>
                            <span>🇾🇪</span> <span>العربية</span>
                        </a>
                        <a href="{{ route('lang.switch', 'nl') }}" class="flex items-center gap-2 px-3 py-2 text-stone-200 hover:bg-[#E07513]/20 hover:text-[#E07513] transition-colors"  wire:navigate>
                            <span>🇳🇱</span> <span>Nederlands</span>
                        </a>
                        <a href="{{ route('lang.switch', 'en') }}" class="flex items-center gap-2 px-3 py-2 text-stone-200 hover:bg-[#E07513]/20 hover:text-[#E07513] transition-colors" wire:navigate>
                            <span>🇬🇧</span> <span>English</span>

                        </a>
                    </div>
                </div>
            </div>

            <!-- المنطقة الثانية (الوسط): الشعار الرسمي مطابق للوجو -->
            <div class="shrink-0 text-center px-3">
                <a href="#home" class="flex flex-col items-center group">
                    <img src="{{ asset('images/logo.jpg') }}" alt="المطبخ اليمني - Jemenitische Keuken" class="h-12 w-auto object-contain transition-transform group-hover:scale-105">
                    <span class="font-extrabold text-base text-[#E07513] tracking-wide leading-none mt-1">المطبخ اليمني</span>
                    <span class="font-bold text-[9px] text-white tracking-widest uppercase font-['Plus_Jakarta_Sans']">JEMENITISCHE KEUKEN</span>
                </a>
            </div>

            <!-- المنطقة الثالثة (اليمين): 3 أزرار تنقل + زر الحجز المباشر -->
            <div class="flex items-center gap-4 lg:gap-6 flex-1 justify-end">
                <nav class="hidden lg:flex items-center gap-6 font-bold text-sm text-stone-200">
                    <a href="#gallery" class="hover:text-[#E07513] transition-colors"  wire:navigate>{{ __('messages.nav.gallery') }}</a>
                    <a href="#contact" class="hover:text-[#E07513] transition-colors" wire:navigate>{{ __('messages.nav.contact') }}</a>
                    <a href="#reservation" class="text-amber-400 hover:text-white transition-colors" wire:navigate>{{ __('messages.nav.reservation') }}</a>
                </nav>

                <a href="#reservation" class="bg-gradient-to-r from-[#E07513] to-[#B85709] hover:from-[#c2620a] hover:to-[#9a4504] text-white px-4 sm:px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm transition-all shadow-lg shadow-[#E07513]/25 hover:shadow-xl hover:-translate-y-0.5" wire:navigate>
                    {{ __('messages.nav.bookTableBtn') }}
                </a>
            </div>

        </div>
    </header>

    <!-- محتوى الصفحة الفردية (Single Page Sections) -->
    <main>
        {{ $slot }}
    </main>

    <!-- الذيل (Footer) مع الشعار كخلفية مائية (Watermark) -->
    <footer class="bg-[#1C0806] text-stone-300 relative overflow-hidden border-t border-[#E07513]/25 pt-16 pb-12">

        <!-- العلامة المائية للشعار في الخلفية -->
        <div class="absolute -bottom-16 left-1/2 -translate-x-1/2 pointer-events-none opacity-5 select-none">
            <img src="{{ asset('images/logo.jpg') }}" alt="Watermark" class="w-[550px] h-auto object-contain filter grayscale invert">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pb-12 border-b border-white/10">

            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-10 w-auto">
                    <div>
                        <h4 class="font-extrabold text-white text-base">المطبخ اليمني</h4>
                        <span class="text-[10px] text-stone-400 block font-['Plus_Jakarta_Sans']">JEMENITISCHE KEUKEN</span>
                    </div>
                </div>
                <p class="text-xs text-stone-400 leading-relaxed">
                    {{ __('messages.brand.description') }}
                </p>
            </div>

            <div>
                <h4 class="text-white text-sm font-extrabold uppercase mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#E07513]"></span>
                    <span>روابط سريعة</span>
                </h4>
                <ul class="space-y-2 text-xs text-stone-400">
                    <li><a href="#home" class="hover:text-[#E07513]">{{ __('messages.nav.home') }}</a></li>
                    <li><a href="#about" class="hover:text-[#E07513]">{{ __('messages.nav.about') }}</a></li>
                    <li><a href="#menu" class="hover:text-[#E07513]">{{ __('messages.nav.menu') }}</a></li>
                    <li><a href="#gallery" class="hover:text-[#E07513]">{{ __('messages.nav.gallery') }}</a></li>
                    <li><a href="#reservation" class="hover:text-[#E07513] font-bold text-amber-300">{{ __('messages.nav.reservation') }}</a></li>
                </ul>
            </div>



            {{-- <div>
                <h4 class="text-white text-sm font-extrabold uppercase mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#E07513]"></span>
                    <span>{{ __('messages.contact.addressTitle') }}</span>
                </h4>
                <p class="text-xs text-stone-400">{{ __('messages.contact.addressValue') }}</p>
                <p class="text-xs font-mono font-bold text-stone-200 mt-2" dir="ltr">{{ __('messages.contact.phoneValue') }}</p>
            </div> --}}

        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-stone-500 relative z-10">
            <p>{{ __('messages.footer.rights') }}</p>
            <p class="text-[#E07513] font-bold">The Origin Of Mandi • أصل المندي</p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
