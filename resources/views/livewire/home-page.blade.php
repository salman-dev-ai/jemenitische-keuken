{{-- resources/views/livewire/home-page.blade.php --}}
@php
    $locale = app()->getLocale();
    $isArabic = $locale === 'ar';
    $isDutch = $locale === 'nl';


    $content = $isDutch
        ? [
            'eyebrow' => 'EEN AUTHENTIEKE YEMENITISCHE SMAAKREIS',
            'title' => "De smaak van\nHadramout\nin elke hap",
            'subtitle' => 'Traditionele Yemenitische gerechten met eeuwenoude smaken, rechtstreeks uit het hart van Jemen.',
            'orderTitle' => 'Bestel online',
            'orderSubtitle' => 'Geniet eenvoudig van onze authentieke Yemenitische gerechten',
            'delivery' => 'Bezorging',
            'deliveryText' => 'Vers bij je thuis',
            'pickup' => 'Afhalen',
            'pickupText' => 'Klaar wanneer jij komt',
            'dinein' => 'In het restaurant',
            'dineinText' => 'Een unieke Yemenitische ervaring',
        ]
        : ($isArabic
            ? [
                'eyebrow' => 'رحلة نكهة يمنية أصيلة',
                'title' => "مذاق حضرموت\nفي كل لقمة",
                'subtitle' => 'أطباق يمنية تقليدية بنكهة عريقة، تأخذك إلى قلب اليمن.',
                'orderTitle' => 'اطلب أونلاين',
                'orderSubtitle' => 'استمتع بأشهى الأطباق اليمنية بكل سهولة',
                'delivery' => 'توصيل',
                'deliveryText' => 'نصل إليك أينما كنت',
                'pickup' => 'استلام',
                'pickupText' => 'جاهز عندك',
                'dinein' => 'أكل في المطعم',
                'dineinText' => 'تجربة يمنية لا تُنسى',
            ]
            : [
                'eyebrow' => 'AN AUTHENTIC YEMENI FLAVOUR JOURNEY',
                'title' => "The taste of\nHadramout\nin every bite",
                'subtitle' => 'Traditional Yemeni dishes with ancient flavours that take you to the heart of Yemen.',
                'orderTitle' => 'Order online',
                'orderSubtitle' => 'Enjoy our authentic Yemeni dishes with ease',
                'delivery' => 'Delivery',
                'deliveryText' => 'Fresh to your door',
                'pickup' => 'Pick-up',
                'pickupText' => 'Ready when you arrive',
                'dinein' => 'Dine in',
                'dineinText' => 'An unforgettable Yemeni experience',
            ]);

    $slides = [
        [
            'image' => asset('images/restaurant/home-hero.webp'),
            'alt' => $isArabic
                ? 'شبام حضرموت والبحر وقت الغروب'
                : ($isDutch
                    ? 'Shibam Hadramout aan de kust van Jemen bij zonsondergang'
                    : 'Shibam Hadramout beside the sea at sunset'),
            'eyebrow' => $isArabic
                ? 'رحلة نكهة يمنية أصيلة'
                : ($isDutch
                    ? 'EEN AUTHENTIEKE YEMENITISCHE SMAAKREIS'
                    : 'AN AUTHENTIC YEMENI FLAVOUR JOURNEY'),
            'title' => $isArabic
                ? "مذاق حضرموت\nفي كل لقمة"
                : ($isDutch
                    ? "De smaak van\nHadramout\nin elke hap"
                    : "The taste of\nHadramout\nin every bite"),
            'text' => $isArabic
                ? 'أطباق يمنية تقليدية بنكهة عريقة، تأخذك إلى قلب اليمن.'
                : ($isDutch
                    ? 'Traditionele Yemenitische gerechten met eeuwenoude smaken, rechtstreeks uit het hart van Jemen.'
                    : 'Traditional Yemeni dishes with ancient flavours that take you to the heart of Yemen.'),
        ],
        [
            'image' => asset('images/restaurant/restaurant-feast.webp'),
            'alt' => $isArabic
                ? 'مائدة يمنية عامرة بالأطباق'
                : ($isDutch
                    ? 'Een rijk gevulde Yemenitische tafel'
                    : 'A generous Yemeni feast table'),
            'eyebrow' => $isArabic
                ? 'كرم الضيافة اليمنية'
                : ($isDutch
                    ? 'YEMENITISCHE GASTVRIJHEID'
                    : 'YEMENI HOSPITALITY'),
            'title' => $isArabic
                ? "سفرة تجمعنا\nعلى الخير"
                : ($isDutch
                    ? "Een tafel\nvol warmte"
                    : "A table\nfull of warmth"),
            'text' => $isArabic
                ? 'نكهات غنية وأجواء دافئة تجعل كل زيارة ذكرى جميلة.'
                : ($isDutch
                    ? 'Rijke smaken en warme momenten die van elk bezoek een mooie herinnering maken.'
                    : 'Rich flavours and warm moments that make every visit memorable.'),
        ],
        [
            'image' => asset('images/restaurant/order-dinein.webp'),
            'alt' => $isArabic
                ? 'طبق مندي يمني شهي'
                : ($isDutch
                    ? 'Een heerlijke Yemenitische mandi-schotel'
                    : 'A delicious Yemeni mandi platter'),
            'eyebrow' => $isArabic ? 'مذاق يُحضّر بشغف' : ($isDutch ? 'MET LIEFDE BEREID' : 'PREPARED WITH PASSION'),
            'title' => $isArabic
                ? "مندي شهي\nبطعم أصيل"
                : ($isDutch
                    ? "Mandi met\nauthentieke smaak"
                    : "Mandi with\nauthentic flavour"),
            'text' => $isArabic
                ? 'أرز معطر وبهارات يمنية ولحم طري، في طبق واحد لا يُنسى.'
                : ($isDutch
                    ? 'Geurige rijst, Yemenitische specerijen en mals vlees in één onvergetelijk gerecht.'
                    : 'Fragrant rice, Yemeni spices and tender meat in one unforgettable dish.'),
        ],
    ];

    // بطاقات "اطلب أونلاين" مع أيقونات Lucide (تُمرّر كـ key للتبديل في الـ blade)
    $orderOptions = [
        ['key' => 'truck',        'title' => $content['delivery'], 'description' => $content['deliveryText'], 'image' => 'order-delivery.webp'],
        ['key' => 'shopping-bag', 'title' => $content['pickup'],   'description' => $content['pickupText'],   'image' => 'order-pickup.webp'],
        ['key' => 'utensils',     'title' => $content['dinein'],   'description' => $content['dineinText'],   'image' => 'order-dinein.webp'],
    ];
@endphp

{{-- الهوم فقط: لا يحتوي هذا الملف على الهيدر أو الفوتر --}}
<div id="home" dir='ltr' class="home-page overflow-hidden bg-[#FBF7ED] text-[#57151B]">

    {{-- ============================================================
         Hero: Carousel بثلاث شرائح + نص متحرك + أيقونات Lucide
         ============================================================ --}}
    <section
        x-data="{
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
            goTo(index) { this.activeSlide = index; this.start(); }
        }"
        x-init="
            start();
            document.addEventListener('visibilitychange', () => document.hidden ? pause() : start());
        "
        @mouseenter="pause()" @mouseleave="start()"
        @focusin="pause()" @focusout="start()"
        @keydown.right.prevent="next()" @keydown.left.prevent="previous()"
        tabindex="0"
        aria-roledescription="carousel"
        aria-label="{{ $isArabic ? 'صور المطعم' : ($isDutch ? 'Restaurantfoto\'s' : 'Restaurant images') }}"
        class="relative isolate min-h-[560px] overflow-hidden bg-[#4A0D12] text-[#FFF9EE] outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-[#D9B36B] sm:min-h-[650px] lg:min-h-[calc(100svh-78px)] lg:max-h-[760px]">

        {{-- شرائح الصور --}}
        <template x-for="(slide, index) in slides" :key="index">
            <div
                x-show="activeSlide === index"
                x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0 scale-[1.03]"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-500 absolute inset-0"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0">

                <img
                    :src="slide.image"
                    :alt="slide.alt"
                    width="1600"
                    height="1000"
                    :loading="index === 0 ? 'eager' : 'lazy'"
                    :fetchpriority="index === 0 ? 'high' : 'auto'"
                    decoding="async"
                    class="absolute inset-0 h-full w-full object-cover object-center transition-transform duration-[7000ms] ease-out will-change-transform"
                    :class="activeSlide === index ? 'scale-105' : 'scale-100'">

                {{-- طبقات تدرج لضمان وضوح النص --}}
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-l from-[#4A0D12]/95 via-[#4A0D12]/60 to-[#4A0D12]/5"></div>
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-[#4A0D12]/75 via-transparent to-transparent"></div>
            </div>
        </template>

        {{-- زخرفة جانبية --}}
        <div aria-hidden="true"
            class="pointer-events-none absolute inset-y-0 end-0 z-10 w-16 opacity-20 [background-image:linear-gradient(135deg,transparent_0%,transparent_44%,#D9B36B_45%,transparent_47%,transparent_100%)] [background-size:48px_48px]">
        </div>

        {{-- المحتوى النصي --}}
        <div class="relative z-20 flex min-h-[560px] items-end justify-center px-7 pb-14 sm:min-h-[650px] sm:px-12 sm:pb-20 lg:min-h-[calc(100svh-78px)] lg:items-center lg:justify-end lg:px-16 lg:pb-0 xl:px-24">
            <div class="w-full max-w-[500px] text-center lg:text-right">
                <template x-for="(slide, index) in slides" :key="`copy-${index}`">
                    <div
                        x-show="activeSlide === index"
                        x-transition:enter="transition ease-out duration-700 delay-150"
                        x-transition:enter-start="opacity-0 translate-y-6"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="text-copy">

                        {{-- Eyebrow --}}
                        <div class="mb-6 flex items-center justify-center gap-4 text-[11px] font-bold tracking-[0.08em] text-[#D9B36B] lg:justify-end">
                            <span class="h-px w-10 bg-[#D9B36B]"></span>
                            <span x-text="slide.eyebrow"></span>
                            <span class="h-px w-10 bg-[#D9B36B] lg:hidden"></span>
                        </div>

                        {{-- Title --}}
                        <h1
                            class="whitespace-pre-line font-serif text-[44px] font-bold leading-[1.12] tracking-[-0.035em] text-[#FFF9EE] drop-shadow-[0_3px_18px_rgba(35,4,7,0.5)] sm:text-6xl lg:text-[70px]"
                            x-text="slide.title"></h1>

                        {{-- Subtitle --}}
                        <p
                            class="mx-auto mt-7 max-w-[410px] text-sm leading-8 text-[#F0DCC5] drop-shadow-md lg:mx-0"
                            x-text="slide.text"></p>

                        {{-- فاصل زخرفي بـ Lucide Diamond --}}
                        <div class="mt-8 flex items-center justify-center gap-4 lg:justify-end">
                            <span class="h-px w-12 bg-[#D9B36B]"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round" class="text-[#D9B36B]" aria-hidden="true">
                                <path d="M2.7 10.3a2.41 2.41 0 0 0 0 3.41l7.59 7.59a2.41 2.41 0 0 0 3.41 0l7.59-7.59a2.41 2.41 0 0 0 0-3.41l-7.59-7.59a2.41 2.41 0 0 0-3.41 0Z"/>
                            </svg>
                            <span class="h-px w-12 bg-[#D9B36B]"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- ============================================================
             أزرار التنقل يمين/يسار — Lucide Chevrons
             ============================================================ --}}
        <button
            type="button"
            @click="previous(); start()"
            aria-label="{{ $isArabic ? 'الصورة السابقة' : ($isDutch ? 'Vorige dia' : 'Previous slide') }}"
            class="group absolute start-4 top-1/2 z-30 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-[#D9B36B]/40 bg-[#4A0D12]/40 text-[#D9B36B] shadow-lg backdrop-blur-md transition-all duration-300 hover:scale-105 hover:border-[#D9B36B] hover:bg-[#D9B36B] hover:text-[#4A0D12] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFF9EE] sm:h-12 sm:w-12">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"
                aria-hidden="true">
                @if ($isArabic)
                    <path d="m9 18 6-6-6-6"/>
                @else
                    <path d="m15 18-6-6 6-6"/>
                @endif
            </svg>
        </button>

        <button
            type="button"
            @click="next(); start()"
            aria-label="{{ $isArabic ? 'الصورة التالية' : ($isDutch ? 'Volgende dia' : 'Next slide') }}"
            class="group absolute end-4 top-1/2 z-30 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-[#D9B36B]/40 bg-[#4A0D12]/40 text-[#D9B36B] shadow-lg backdrop-blur-md transition-all duration-300 hover:scale-105 hover:border-[#D9B36B] hover:bg-[#D9B36B] hover:text-[#4A0D12] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFF9EE] sm:h-12 sm:w-12">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"
                aria-hidden="true">
                @if ($isArabic)
                    <path d="m15 18-6-6 6-6"/>
                @else
                    <path d="m9 18 6-6-6-6"/>
                @endif
            </svg>
        </button>

        {{-- ============================================================
             مؤشر الشرائح — Pill حديث ينمو عند التفعيل
             ============================================================ --}}
        <div class="absolute bottom-6 start-1/2 z-30 -translate-x-1/2" role="tablist"
            aria-label="{{ $isArabic ? 'شرائح الصور' : ($isDutch ? 'Afbeeldingsdia\'s' : 'Image slides') }}"
            dir="ltr">
            <div class="flex items-center gap-1.5 rounded-full border border-[#FDFBF7]/15 bg-[#2C0D0A]/55 px-3 py-2 shadow-[0_8px_30px_rgba(0,0,0,0.35)] backdrop-blur-xl">
                <template x-for="(slide, index) in slides" :key="`dot-${index}`">
                    <button
                        type="button"
                        @click="goTo(index)"
                        :aria-label="`{{ $isArabic ? 'انتقل إلى الصورة' : ($isDutch ? 'Ga naar dia' : 'Go to slide') }} ${index + 1}`"
                        :aria-selected="activeSlide === index"
                        role="tab"
                        class="h-1.5 rounded-full transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#D9B36B]/70 focus-visible:ring-offset-2 focus-visible:ring-offset-[#2C0D0A]"
                        :class="activeSlide === index
                            ? 'w-8 bg-gradient-to-r from-[#D9B36B] to-[#E8C88A] shadow-[0_0_12px_rgba(217,179,107,0.55)]'
                            : 'w-1.5 bg-[#FDFBF7]/45 hover:bg-[#FDFBF7]/80 hover:scale-125'">
                    </button>
                </template>
            </div>
        </div>
    </section>

    {{-- ============================================================
         اطلب أونلاين — بطاقات مع أيقونات Lucide
         ============================================================ --}}
    <section   class="relative bg-[#FBF7ED] px-4 py-14 sm:px-6 sm:py-20 lg:px-8 lg:py-24">
        {{-- خلفية مائية خفيفة --}}
        <div aria-hidden="true"
            class="pointer-events-none absolute inset-0 opacity-[0.08] [background-image:radial-gradient(#C89B4A_1px,transparent_1px)] [background-size:36px_36px]">
        </div>

        <div class="relative mx-auto max-w-6xl">
            {{-- رأس القسم --}}
            <div class="text-center">
                <div class="flex items-center justify-center gap-4 text-[#C89B4A]">
                    <span class="h-px w-16 bg-[#C89B4A]"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <path d="M2.7 10.3a2.41 2.41 0 0 0 0 3.41l7.59 7.59a2.41 2.41 0 0 0 3.41 0l7.59-7.59a2.41 2.41 0 0 0 0-3.41l-7.59-7.59a2.41 2.41 0 0 0-3.41 0Z"/>
                    </svg>
                    <span class="h-px w-16 bg-[#C89B4A]"></span>
                </div>

                <h2 class="mt-3 font-serif text-4xl font-bold tracking-[-0.04em] text-[#57151B] sm:text-5xl">
                    {{ $content['orderTitle'] }}
                </h2>

                <p class="mt-3 text-sm text-[#B5863C] sm:text-base">
                    {{ $content['orderSubtitle'] }}
                </p>
            </div>

            {{-- البطاقات --}}
            <div class="mt-10 grid gap-6 md:grid-cols-3">
                @foreach ($orderOptions as $option)
                    <a href="#reservation"
                        class="group relative overflow-hidden rounded-xl border border-[#D9C9B4] bg-[#FFFDF8] shadow-[0_5px_16px_rgba(87,21,27,0.05)] transition-all duration-300 hover:-translate-y-1 hover:border-[#C89B4A] hover:shadow-[0_14px_28px_rgba(87,21,27,0.12)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#C89B4A]">

                        {{-- صورة البطاقة --}}
                        <div class="relative h-32 overflow-hidden sm:h-36">
                            <img src="{{ asset('images/restaurant/' . $option['image']) }}"
                                alt="{{ $option['title'] }}"
                                width="800" height="420"
                                loading="lazy"
                                decoding="async"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#57151B]/65 via-transparent to-transparent"></div>
                        </div>

                        {{-- محتوى البطاقة --}}
                        <div class="relative px-5 pb-5 pt-0 text-center">
                            {{-- أيقونة دائرية متداخلة (Lucide) --}}
                            <span class="relative -mt-6 mx-auto flex h-12 w-12 items-center justify-center rounded-full border-2 border-[#FBF7ED] bg-[#57151B] text-[#D9B36B] shadow-md transition-transform duration-300 group-hover:scale-105"
                                aria-hidden="true">
                                @switch($option['key'])
                                    @case('truck')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/>
                                            <path d="M15 18H9"/>
                                            <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/>
                                            <circle cx="17" cy="18" r="2"/>
                                            <circle cx="7" cy="18" r="2"/>
                                        </svg>
                                        @break

                                    @case('shopping-bag')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                                            <path d="M3 6h18"/>
                                            <path d="M16 10a4 4 0 0 1-8 0"/>
                                        </svg>
                                        @break

                                    @case('utensils')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/>
                                            <path d="M7 2v20"/>
                                            <path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>
                                        </svg>
                                        @break
                                @endswitch
                            </span>

                            <h3 class="mt-3 font-serif text-2xl font-bold text-[#57151B]">
                                {{ $option['title'] }}
                            </h3>

                            <p class="mt-1 text-xs text-[#B5863C]">
                                {{ $option['description'] }}
                            </p>

                            {{-- CTA: أيقونة Lucide Arrow --}}
                            <span
                                class="mx-auto mt-5 flex h-9 w-9 items-center justify-center rounded-full bg-[#C89B4A] text-[#FFF9EE] shadow-sm transition-all duration-300 group-hover:bg-[#B5863C] group-hover:shadow-md"
                                aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="transition-transform duration-300 {{ $isArabic ? 'group-hover:-translate-x-0.5' : 'group-hover:translate-x-0.5' }}">
                                    @if ($isArabic)
                                        <path d="m12 19-7-7 7-7"/>
                                        <path d="M19 12H5"/>
                                    @else
                                        <path d="M5 12h14"/>
                                        <path d="m12 5 7 7-7 7"/>
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

