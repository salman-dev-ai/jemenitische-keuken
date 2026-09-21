{{-- الهوم فقط: لا يحتوي هذا الملف على الهيدر أو الفوتر --}}
<div id="home" dir='ltr' class="home-page overflow-hidden bg-[#FBF7ED] text-[#57151B]">

    {{-- ============================================================
         Hero: Carousel بثلاث شرائح + نص متحرك + أيقونات Lucide
         ============================================================ --}}
    <section x-data="{
        activeSlide: 0,
        timer: null,
        slides: @js($slides),
        start() {
            this.pause();
            this.timer = setInterval(() => this.next(), 3000);
        },
        pause() {
            if (this.timer) clearInterval(this.timer);
            this.timer = null;
        },
        next() { this.activeSlide = (this.activeSlide + 1) % this.slides.length; },
        previous() { this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length; },
        goTo(index) { this.activeSlide = index;
            this.start(); }
    }" x-init="start();
    document.addEventListener('visibilitychange', () => document.hidden ? pause() : start());" @mouseenter="pause()" @mouseleave="start()" @focusin="pause()"
        @focusout="start()" @keydown.right.prevent="next()" @keydown.left.prevent="previous()" tabindex="0"
        aria-roledescription="carousel"
        aria-label="{{ $isArabic ? 'صور المطعم' : ($isDutch ? 'Restaurantfoto\'s' : 'Restaurant images') }}"
        class="relative isolate min-h-[560px] overflow-hidden bg-[#4A0D12] text-[#FFF9EE] outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-[#D9B36B] sm:min-h-[650px] lg:min-h-[calc(100svh-78px)] lg:max-h-[760px]">

        {{-- شرائح الصور --}}
        <template x-for="(slide, index) in slides" :key="index">
            <div x-show="activeSlide === index" x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0 scale-[1.03]" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-500 absolute inset-0"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0">

                {{-- تم الاعتماد على slide.image المجهزة من Livewire والتي تحتوي على مسار Storage --}}
                <img :src="slide.image" :alt="slide.title" width="1600" height="1000"
                    :loading="index === 0 ? 'eager' : 'lazy'" :fetchpriority="index === 0 ? 'high' : 'auto'"
                    decoding="async"
                    class="absolute inset-0 h-full w-full object-cover object-center transition-transform duration-[7000ms] ease-out will-change-transform"
                    :class="activeSlide === index ? 'scale-105' : 'scale-100'">

                طبقات تدرج لضمان وضوح النص
                <div
                    class="pointer-events-none absolute inset-0 bg-gradient-to-l from-[#4A0D12]/95 via-[#4A0D12]/60 to-[#4A0D12]/5">
                </div>
                <div
                    class="pointer-events-none absolute inset-0 bg-gradient-to-t from-[#4A0D12]/75 via-transparent to-transparent">
                </div>
            </div>
        </template>

        {{-- المحتوى النصي --}}
        <div
            class="relative z-20 flex min-h-[560px] items-end justify-center px-7 pb-14 sm:min-h-[650px] sm:px-12 sm:pb-20 lg:min-h-[calc(100svh-78px)] lg:items-center lg:justify-end lg:px-16 lg:pb-0 xl:px-24">
            <div class="w-full max-w-[500px] text-center lg:text-right">
                <template x-for="(slide, index) in slides" :key="`copy-${index}`">
                    <div x-show="activeSlide === index" x-transition:enter="transition ease-out duration-700 delay-150"
                        x-transition:enter-start="opacity-0 translate-y-6"
                        x-transition:enter-end="opacity-100 translate-y-0" class="text-copy">

                        {{-- Eyebrow --}}
                        <div
                            class="mb-6 flex items-center justify-center gap-4 text-[11px] font-bold tracking-[0.08em] text-[#D9B36B] lg:justify-end">
                            <span class="h-px w-10 bg-[#D9B36B]"></span>
                            <span x-text="slide.eyebrow"></span>
                            <span class="h-px w-10 bg-[#D9B36B] lg:hidden"></span>
                        </div>

                        {{-- Title --}}
                        <h1 class="whitespace-pre-line font-serif text-[44px] font-bold leading-[1.12] tracking-[-0.035em] text-[#FFF9EE] drop-shadow-[0_3px_18px_rgba(35,4,7,0.5)] sm:text-6xl lg:text-[70px]"
                            x-text="slide.title"></h1>

                        {{-- Subtitle --}}
                        <p class="mx-auto mt-7 max-w-[410px] text-sm leading-8 text-[#F0DCC5] drop-shadow-md lg:mx-0"
                            x-text="slide.text"></p>

                        {{-- فاصل زخرفي --}}
                        <div class="mt-8 flex items-center justify-center gap-4 lg:justify-end">
                            <span class="h-px w-12 bg-[#D9B36B]"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round" class="text-[#D9B36B]" aria-hidden="true">
                                <path
                                    d="M2.7 10.3a2.41 2.41 0 0 0 0 3.41l7.59 7.59a2.41 2.41 0 0 0 3.41 0l7.59-7.59a2.41 2.41 0 0 0 0-3.41l-7.59-7.59a2.41 2.41 0 0 0-3.41 0Z" />
                            </svg>
                            <span class="h-px w-12 bg-[#D9B36B]"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- أزرار التنقل --}}
        <button type="button" @click="previous(); start()"
            aria-label="{{ $isArabic ? 'الصورة السابقة' : ($isDutch ? 'Vorige dia' : 'Previous slide') }}"
            class="group absolute start-4 top-1/2 z-30 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-[#D9B36B]/40 bg-[#4A0D12]/40 text-[#D9B36B] shadow-lg backdrop-blur-md transition-all duration-300 hover:scale-105 hover:border-[#D9B36B] hover:bg-[#D9B36B] hover:text-[#4A0D12] sm:h-12 sm:w-12">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"
                aria-hidden="true">
                <path d="m15 18-6-6 6-6" />
            </svg>
        </button>

        <button type="button" @click="next(); start()"
            aria-label="{{ $isArabic ? 'الصورة التالية' : ($isDutch ? 'Volgende dia' : 'Next slide') }}"
            class="group absolute end-4 top-1/2 z-30 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-[#D9B36B]/40 bg-[#4A0D12]/40 text-[#D9B36B] shadow-lg backdrop-blur-md transition-all duration-300 hover:scale-105 hover:border-[#D9B36B] hover:bg-[#D9B36B] hover:text-[#4A0D12] sm:h-12 sm:w-12">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"
                aria-hidden="true">
                <path d="m9 18 6-6-6-6" />
            </svg>
        </button>

        {{-- مؤشر الشرائح --}}
        <div class="absolute bottom-6 start-1/2 z-30 -translate-x-1/2" role="tablist"
            aria-label="{{ $isArabic ? 'شرائح الصور' : ($isDutch ? 'Afbeeldingsdia\'s' : 'Image slides') }}"
            dir="ltr">
            <div
                class="flex items-center gap-1.5 rounded-full border border-[#FDFBF7]/15 bg-[#2C0D0A]/55 px-3 py-2 shadow-[0_8px_30px_rgba(0,0,0,0.35)] backdrop-blur-xl">
                <template x-for="(slide, index) in slides" :key="`dot-${index}`">
                    <button type="button" @click="goTo(index)"
                        :aria-label="`{{ $isArabic ? 'انتقل إلى الصورة' : ($isDutch ? 'Ga naar dia' : 'Go to slide') }} ${index + 1}`"
                        :aria-selected="activeSlide === index" role="tab"
                        class="h-1.5 rounded-full transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#D9B36B]/70"
                        :class="activeSlide === index ?
                            'w-8 bg-gradient-to-r from-[#D9B36B] to-[#E8C88A] shadow-[0_0_12px_rgba(217,179,107,0.55)]' :
                            'w-1.5 bg-[#FDFBF7]/45 hover:bg-[#FDFBF7]/80 hover:scale-125'">
                    </button>
                </template>
            </div>
        </div>
    </section>

    {{-- ============================================================
         اطلب أونلاين — بطاقات مع أيقونات Lucide
         ============================================================ --}}
    <section class="relative bg-[#FBF7ED] px-4 py-14 sm:px-6 sm:py-20 lg:px-8 lg:py-24">
        <div aria-hidden="true"
            class="pointer-events-none absolute inset-0 opacity-[0.08] [background-image:radial-gradient(#C89B4A_1px,transparent_1px)] [background-size:36px_36px]">
        </div>

        <div class="relative mx-auto max-w-6xl">
            {{-- رأس القسم تم تحويله ليدعم اللغات بشكل مباشر دون الحاجة لمصفوفة content --}}
            <div class="text-center">
                <div class="flex items-center justify-center gap-4 text-[#C89B4A]">
                    <span class="h-px w-16 bg-[#C89B4A]"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <path
                            d="M2.7 10.3a2.41 2.41 0 0 0 0 3.41l7.59 7.59a2.41 2.41 0 0 0 3.41 0l7.59-7.59a2.41 2.41 0 0 0 0-3.41l-7.59-7.59a2.41 2.41 0 0 0-3.41 0Z" />
                    </svg>
                    <span class="h-px w-16 bg-[#C89B4A]"></span>
                </div>

                <h2 class="mt-3 font-serif text-4xl font-bold tracking-[-0.04em] text-[#57151B] sm:text-5xl">
                    {{ $isArabic ? 'خيارات الطلب' : ($isDutch ? 'Bestelopties' : 'Order Options') }}
                </h2>

                <p class="mt-3 text-sm text-[#B5863C] sm:text-base">
                    {{ $isArabic ? 'اختر الطريقة التي تناسبك لتجربة أطباقنا' : ($isDutch ? 'Kies hoe u van onze gerechten wilt genieten' : 'Choose how you want to experience our dishes') }}
            </div>

            {{-- البطاقات --}}
         <div class="mt-10 grid gap-6 md:grid-cols-3">
    @foreach ($orderOptions as $option)
        <a href="#reservation"
            class="group relative overflow-hidden rounded-xl border border-[#D9C9B4] bg-[#FFFDF8] shadow-[0_5px_16px_rgba(87,21,27,0.05)] transition-all duration-300 hover:-translate-y-1 hover:border-[#C89B4A] hover:shadow-[0_14px_28px_rgba(87,21,27,0.12)]">

            {{-- صورة البطاقة (تستقبل الرابط المباشر من المودل/Livewire) --}}
            <div class="relative h-32 overflow-hidden sm:h-36">
                <img src="{{ $option['image'] }}" alt="{{ $option['title'] }}" width="800"
                    height="420" loading="lazy" decoding="async"
                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                <div
                    class="absolute inset-0 bg-gradient-to-t from-[#57151B]/65 via-transparent to-transparent">
                </div>
            </div>

            {{-- محتوى البطاقة --}}
            <div class="relative px-5 pb-5 pt-0 text-center">
                <span
                    class="relative -mt-6 mx-auto flex h-12 w-12 items-center justify-center rounded-full border-2 border-[#FBF7ED] bg-[#57151B] shadow-md transition-transform duration-300 group-hover:scale-105"
                    aria-hidden="true">

                    @php
                        $iconName = match ($option['key']) {
                            'truck'        => 'heroicon-o-truck',
                            'shopping-bag' => 'heroicon-o-shopping-bag',
                            'utensils'     => 'heroicon-o-cake',
                            'clock'        => 'heroicon-o-clock',
                            'map-pin'      => 'heroicon-o-map-pin',
                            'credit-card'  => 'heroicon-o-credit-card',
                            'package'      => 'heroicon-o-cube',
                            'store'        => 'heroicon-o-building-storefront',
                            default        => 'heroicon-o-squares-2x2',
                        };
                    @endphp

                    {{-- تم إغلاق الوسم هنا بشكل صحيح وتنظيف كلاس الألوان --}}
                    <x-dynamic-component :component="$iconName" class="w-6 h-6 text-[#D9B36B]" />

                </span>

                <h3 class="mt-3 font-serif text-2xl font-bold text-[#57151B]">
                    {{ $option['title'] }}
                </h3>

                <p class="mt-1 text-xs text-[#B5863C]">
                    {{ $option['description'] }}
                </p>

                <span
                    class="mx-auto mt-5 flex h-9 w-9 items-center justify-center rounded-full bg-[#C89B4A] text-[#FFF9EE] shadow-sm transition-all duration-300 group-hover:bg-[#B5863C] group-hover:shadow-md"
                    aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                        class="transition-transform duration-300 {{ $isArabic ? 'group-hover:-translate-x-0.5' : 'group-hover:translate-x-0.5' }}">
                        @if ($isArabic)
                            <path d="m12 19-7-7 7-7" />
                            <path d="M19 12H5" />
                        @else
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        @endif
                    </svg>
                </span>
            </div>
        </a>
    @endforeach
</div>

        </div>
    </section>

    {{-- زخرفة نهاية الهوم --}}
    <div aria-hidden="true"
        class="h-5 w-full border-t border-[#C89B4A]/20 bg-[#FBF7ED] [background-image:linear-gradient(135deg,transparent_0%,transparent_42%,#C89B4A_43%,transparent_45%,transparent_100%)] [background-size:34px_34px] opacity-70">
    </div>
</div>
