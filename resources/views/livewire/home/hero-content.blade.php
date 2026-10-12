{{--
    نص الهيرو: يعرض السطر التمهيدي والعنوان والوصف وفاصلاً زخرفياً لكل شريحة بحركة دخول من الأسفل، ويظهر نص الشريحة النشطة فقط اعتماداً على متغيرات Alpine.js.
--}}

<div
    class="relative z-20 flex min-h-[560px] items-end justify-center px-7 pb-14 sm:min-h-[650px] sm:px-12 sm:pb-20 lg:min-h-[calc(100svh-78px)] lg:items-center lg:justify-end lg:px-16 lg:pb-0 xl:px-24">
    <div class="w-full max-w-[500px] text-center lg:text-right">

        {{-- حلقة Alpine تكرر كتلة النص لكل شريحة وتظهر النشطة بحركة دخول --}}
        <template x-for="(slide, index) in slides" :key="`copy-${index}`">
            <div x-show="activeSlide === index"
                x-transition:enter="transition ease-out duration-700 delay-150"
                x-transition:enter-start="opacity-0 translate-y-6"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="text-copy">

                {{-- السطر التمهيدي الصغير فوق العنوان مع خطين ذهبيين زخرفيين جانبيين --}}
                <div
                    class="mb-6 flex items-center justify-center gap-4 text-[11px] font-bold tracking-[0.08em] text-[#D9B36B] lg:justify-end">
                    <span class="h-px w-10 bg-[#D9B36B]"></span>
                    <span x-text="slide.eyebrow"></span>
                    <span class="h-px w-10 bg-[#D9B36B] lg:hidden"></span>
                </div>

                {{-- العنوان الرئيسي للشريحة، ويدعم الأسطر المتعددة عبر الكلاس whitespace-pre-line بدون وسوم إضافية --}}
                <h1 class="whitespace-pre-line font-serif text-[44px] font-bold leading-[1.12] tracking-[-0.035em] text-[#FFF9EE] drop-shadow-[0_3px_18px_rgba(35,4,7,0.5)] sm:text-6xl lg:text-[70px]"
                    x-text="slide.title"></h1>

                {{-- الوصف القصير أسفل العنوان بعرض محدود يضمن سهولة القراءة على جميع الشاشات --}}
                <p class="mx-auto mt-7 max-w-[410px] text-sm leading-8 text-[#F0DCC5] drop-shadow-md lg:mx-0"
                    x-text="slide.text"></p>

                {{-- فاصل زخرفي على شكل معين ذهبي بين خطين أسفل الوصف --}}
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
