{{--
    هيكل تحميل الصفحة الرئيسية: أشكال رمادية نابضة تطابق تخطيط الهيرو وبطاقات الطلب أثناء التحميل. حالة اللغة والاتجاه تُدار داخل المتصفح عبر Alpine.js دون طلبات للخادم، ويتغير الاتجاه فوراً عند بث حدث locale-changed.
--}}

{{-- الغلاف الرئيسي: يربط حالة اللغة والاتجاه ويستمع لحدث تغيير اللغة فوراً --}}
<div id="home-skeleton" role="status" aria-busy="true"
    x-data="{
        /* حالة اللغة والاتجاه محلياً: تقرأ من التخزين المحلي ثم لغة الصفحة */
        lang: (() => { try { return localStorage.getItem('locale'); } catch (e) { return null; } })() || document.documentElement.lang || 'ar',
        cards: [1, 2, 3],
        dots: [1, 2, 3],

        /* الاتجاه والنص المقروء يُحسبان من اللغة الحالية بدون أي إعادة تحميل */
        get dir() { return this.lang.slice(0, 2) === 'ar' ? 'rtl' : 'ltr'; },
        get label() { return { ar: 'جار التحميل', nl: 'Laden', en: 'Loading' }[this.lang.slice(0, 2)] ?? 'Loading'; }
    }"
    x-on:locale-changed.window="lang = (typeof $event.detail === 'string' ? $event.detail : $event.detail?.locale) || lang"
    :dir="dir" :lang="lang" :aria-label="label"
    class="home-page overflow-hidden bg-[#FBF7ED] text-[#57151B]">

    {{-- هيكل الهيرو: خلفية داكنة ونصوص وأزرار ومؤشرات رمادية نابضة بنفس الأبعاد --}}
    <section
        class="relative isolate min-h-[560px] overflow-hidden bg-[#4A0D12] text-[#FFF9EE] sm:min-h-[650px] lg:min-h-[calc(100svh-78px)] lg:max-h-[760px]">
        <div dir="ltr"
            class="relative z-20 flex min-h-[560px] items-end justify-center px-7 pb-14 sm:min-h-[650px] sm:px-12 sm:pb-20 lg:min-h-[calc(100svh-78px)] lg:items-center lg:justify-end lg:px-16 lg:pb-0 xl:px-24">
            <div class="w-full max-w-[500px] animate-pulse text-center lg:text-right">

                {{-- كتلة النص: سطر تمهيدي وعنوان وفقرة وفاصل زخرفي بأشرطة رمادية --}}
                <div class="mb-6 flex items-center justify-center gap-4 lg:justify-end">
                    <span class="h-px w-10 bg-[#D9B36B]/60"></span>
                    <span class="h-3 w-24 rounded-full bg-[#D9B36B]/40"></span>
                </div>
                <div class="space-y-3">
                    <div class="mx-auto h-11 w-4/5 rounded-lg bg-[#FFF9EE]/15 lg:ms-auto lg:me-0 lg:h-14"></div>
                    <div class="mx-auto h-11 w-3/5 rounded-lg bg-[#FFF9EE]/15 lg:ms-auto lg:me-0 lg:h-14"></div>
                </div>
                <div class="mx-auto mt-7 max-w-[410px] space-y-3 lg:mx-0 lg:ms-auto">
                    <div class="h-3 w-full rounded-full bg-[#F0DCC5]/20"></div>
                    <div class="h-3 w-11/12 rounded-full bg-[#F0DCC5]/20"></div>
                    <div class="h-3 w-2/3 rounded-full bg-[#F0DCC5]/20"></div>
                </div>
                <div class="mt-8 flex items-center justify-center gap-4 lg:justify-end">
                    <span class="h-px w-12 bg-[#D9B36B]/60"></span>
                    <span class="h-4 w-4 rotate-45 rounded-sm bg-[#D9B36B]/50"></span>
                    <span class="h-px w-12 bg-[#D9B36B]/60"></span>
                </div>
            </div>
        </div>

        {{-- زرا السابق والتالي على الجانبين مع مؤشر النقاط السفلي بأشكال نابضة --}}
        <span
            class="absolute start-4 top-1/2 z-30 h-11 w-11 -translate-y-1/2 animate-pulse rounded-full border border-[#D9B36B]/30 bg-[#4A0D12]/40 sm:h-12 sm:w-12"></span>
        <span
            class="absolute end-4 top-1/2 z-30 h-11 w-11 -translate-y-1/2 animate-pulse rounded-full border border-[#D9B36B]/30 bg-[#4A0D12]/40 sm:h-12 sm:w-12"></span>
        <div dir="ltr" class="absolute bottom-6 start-1/2 z-30 -translate-x-1/2">
            <div
                class="flex animate-pulse items-center gap-1.5 rounded-full border border-[#FDFBF7]/15 bg-[#2C0D0A]/55 px-3 py-2">
                <template x-for="n in dots" :key="`dot-${n}`">
                    <span class="h-1.5 rounded-full bg-[#FDFBF7]/45" :class="n === 1 ? 'w-8' : 'w-1.5'"></span>
                </template>
            </div>
        </div>
    </section>

    {{-- هيكل قسم الطلب: خلفية منقطة ورأس القسم وشبكة بطاقات نابضة --}}
    <section class="relative bg-[#FBF7ED] px-4 py-14 sm:px-6 sm:py-20 lg:px-8 lg:py-24">
        <div aria-hidden="true"
            class="pointer-events-none absolute inset-0 opacity-[0.08] [background-image:radial-gradient(#C89B4A_1px,transparent_1px)] [background-size:36px_36px]">
        </div>

        <div class="relative mx-auto max-w-6xl">

            {{-- رأس القسم: فاصل زخرفي وعنوان ووصف قصير بأشرطة رمادية متمركزة --}}
            <div class="flex animate-pulse flex-col items-center text-center">
                <div class="flex items-center justify-center gap-4">
                    <span class="h-px w-16 bg-[#C89B4A]/60"></span>
                    <span class="h-4 w-4 rotate-45 rounded-sm bg-[#C89B4A]/50"></span>
                    <span class="h-px w-16 bg-[#C89B4A]/60"></span>
                </div>
                <div class="mt-4 h-10 w-56 rounded-lg bg-[#D9C9B4]/70 sm:h-12 sm:w-72"></div>
                <div class="mt-4 h-3.5 w-64 rounded-full bg-[#D9C9B4]/60 sm:w-80"></div>
            </div>

            {{-- بطاقات الطلب: حلقة Alpine تكرر ثلاث بطاقات هيكلية دون حلقات Blade --}}
            <div class="mt-10 grid gap-6 md:grid-cols-3">
                <template x-for="n in cards" :key="`card-${n}`">
                    <div
                        class="relative animate-pulse overflow-hidden rounded-xl border border-[#D9C9B4] bg-[#FFFDF8] shadow-[0_5px_16px_rgba(87,21,27,0.05)]">
                        <div class="relative h-32 bg-[#D9C9B4]/60 sm:h-36"></div>
                        <div class="relative flex flex-col items-center px-5 pb-5 pt-0 text-center">
                            <span
                                class="relative -mt-6 h-12 w-12 rounded-full border-2 border-[#FBF7ED] bg-[#C9B79C] shadow-md"></span>
                            <span class="mt-4 h-6 w-32 rounded-lg bg-[#D9C9B4]"></span>
                            <span class="mt-3 h-3 w-40 rounded-full bg-[#E6DAC6]"></span>
                            <span class="mt-5 h-9 w-9 rounded-full bg-[#D9C9B4]"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </section>

    {{-- الزخرفة الختامية: شريط قطري ذهبي ثابت يطابق نهاية الصفحة الأصلية --}}
    <div aria-hidden="true"
        class="h-5 w-full border-t border-[#C89B4A]/20 bg-[#FBF7ED] [background-image:linear-gradient(135deg,transparent_0%,transparent_42%,#C89B4A_43%,transparent_45%,transparent_100%)] [background-size:34px_34px] opacity-70">
    </div>
</div>
