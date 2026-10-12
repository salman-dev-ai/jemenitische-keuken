{{--
    هيكل الهيدر الثابت.
    يعرّف حالة Alpine للقسم النشط والقوائم، ثم يضع الشعار والنقشة
    ويستدعي روابط التنقل وأزرار الجوال وسطح المكتب.
--}}
{{-- الحالة تُدار في المتصفح دون رحلة إلى الخادم. --}}
<header x-data="{
    mobileOpen: false,
    languageOpen: false,
    activeSection: 'home',
    toggleMobile() { /* يغلق اللغة عند فتح قائمة الجوال */
        this.mobileOpen = !this.mobileOpen;
        this.languageOpen = false;
    },
    toggleLanguage() { /* يغلق الجوال عند فتح قائمة اللغة */
        this.languageOpen = !this.languageOpen;
        this.mobileOpen = false;
    },
    closeMenus() { /* يغلق كل القوائم المفتوحة */
        this.mobileOpen = false;
        this.languageOpen = false;
    }
}" @keydown.escape.window="closeMenus()"
    class="sticky top-0 z-50 w-full overflow-x-clip border-b border-gray-200/30 bg-white/60 shadow-sm backdrop-blur-lg transition-all duration-300">

    <div class="relative w-full text-gray-800" dir="ltr">
        {{-- النقشة في الطرف المقابل للشعار --}}
        <a href="#reservation">
            <div class="pointer-events-none absolute inset-y-0 right-0 z-0 w-48 bg-contain bg-right bg-no-repeat md:pointer-events-auto lg:w-54"
                style="background-image: url('{{ asset('images/pattern.webp') }}');">
            </div>
        </a>

        <div class="relative z-10 mx-auto max-w-7xl px-3 py-2 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-2.5 lg:flex-row lg:items-center lg:justify-between lg:gap-6">
                {{-- الشعار مع أدوات الجوال في الصف نفسه --}}
                <div class="flex w-full items-center justify-between lg:w-auto">
                    <a href="#reservation" @click="activeSection = 'reservation'"
                        class="group flex shrink-0 items-center transition-transform hover:scale-105">
                        <div
                            class="relative z-10 rounded-full border border-[#E07513]/40 bg-white/90 p-0 shadow-md shadow-[#E07513]/15 ring-white/60 backdrop-blur-sm">
                            <img src="{{ asset('images/image.png') }}" alt="المطبخ اليمني"
                                class="h-10 w-10 rounded-full object-cover sm:h-20 sm:w-20 lg:h-26 lg:w-26">
                        </div>
                    </a>

                    @include('components.layouts.partials.header.mobile-tools')
                </div>

                @include('components.layouts.partials.header.nav')

                @include('components.layouts.partials.header.header-actions')
            </div>
        </div>
    </div>
</header>
