{{--
    مسار بطاقات الهيرو بشكل دائري لا نهائي: البطاقات تدور باستمرار في حلقة، والبطاقة التي تصل منتصف المسار هي النشطة وتبرز (تكبر وتتوهج ويظهر عليها التاج والتفاصيل).
    عند آخر بطاقة تتابع الحركة إلى الأولى بنفس الاتجاه دون أي عودة للخلف، لأن المسار يحسب موضعاً متصلاً (pos) لا يعود للصفر، وكل بطاقة تُوضع بإزاحة دائرية عن المركز.
    إن كان عدد الشرائح قليلاً تُكرَّر نسخها (clones) حتى لا تظهر فراغات في الحلقة، وتبقى البطاقة النشطة دائماً من الشريحة الأصلية المقابلة لـ activeSlide.
    أطراف المسار تتلاشى بضبابية ناعمة (mask)، والبطاقات البعيدة عن المنتصف تميل ميلاً خفيفاً نحو المركز فيظهر المسار منحنياً.
    الاتجاه يراعي RTL و LTR فيبقى الانحناء متماثلاً، والتاج متمركز بإحداثيات فيزيائية لا تتأثر باللغة.
    يدعم السحب باللمس (سحبة قصيرة تنقل للتالي أو السابق) بدلاً من التمرير الأفقي القديم، ولا يعتمد على x-ref="track" فلا يتدخل centerActive في السلايدر الأب.
    حقول الشريحة: image, eyebrow, title, text, day, time, url.
    تعتمد على slides و activeSlide و goTo و next و previous و start من Alpine.js في السلايدر الأب.
--}}
{{-- المسار: شبكة بخلية واحدة تتكدس فيها كل البطاقات فيأخذ المسار ارتفاع أطولها، والـ mask يخفي الأطراف بتدرج متماثل --}}
<div
    x-data="{
        n: slides.length,
        /* عدد البطاقات المرسومة: نكرر الشرائح حتى لا يقل العدد عن عشر، فلا يُرى انتقال البطاقة من طرف لآخر */
        m: slides.length < 2 ? slides.length : slides.length * Math.ceil(10 / slides.length),
        /* الموضع المتصل للحلقة: يزيد أو ينقص مع كل انتقال ولا يعود للصفر عند آخر شريحة */
        pos: activeSlide,
        sx: 0,

        /* عند تغير الشريحة النشطة نحرك الموضع بأقصر مسار دائري (خطوة للأمام أو للخلف) بدل القفز */
        step(a) {
            const cur = ((this.pos % this.n) + this.n) % this.n;
            let delta = a - cur;
            if (delta > this.n / 2) delta -= this.n;
            else if (delta <= -this.n / 2) delta += this.n;
            this.pos += delta;
        },

        /* الإزاحة الدائرية للبطاقة j عن المركز: صفر للنشطة، وتلتف عند حدود الحلقة البعيدة خارج الشاشة */
        offset(j) {
            let d = (((j - this.pos) % this.m) + this.m) % this.m;
            if (d >= this.m / 2) d -= this.m;
            return d;
        },

        isActive(j) { return this.offset(j) === 0; },

        /* أنماط البطاقة: إزاحة أفقية بمقدار عرضها مع الفراغ، وميل نحو المركز، وتصغير تدريجي، وإخفاء البعيدة */
        cardStyle(j, rtl) {
            const d = this.offset(j);
            const abs = Math.abs(d);
            /* في RTL يتجه الترتيب البصري عكسياً */
            const x = rtl ? -d : d;
            const side = Math.sign(x);
            const angle = abs === 0 ? 0 : Math.min(abs * 9, 18) * -side;
            const scale = abs === 0 ? 1 : Math.max(0.94 - (abs - 1) * 0.03, 0.86);
            const lift = abs === 0 ? 'var(--lift)' : '0px';
            const opacity = abs === 0 ? 1 : (abs <= 2 ? 0.85 : 0);
            /* البطاقات البعيدة (خارج الشاشة) تنتقل دون حركة، وهذا ما يخفي التفافها عند طرفي الحلقة */
            const tr = abs <= 3 ? 'transform 700ms cubic-bezier(.4,0,.2,1), opacity 500ms ease, box-shadow 500ms ease' : 'none';
            return `transform: translateX(calc(${x} * (100% + var(--gap)))) translateY(${lift}) perspective(1400px) rotateY(${angle}deg) scale(${scale}); opacity: ${opacity}; z-index: ${10 - Math.min(abs, 9)}; transition: ${tr}; pointer-events: ${abs > 2 ? 'none' : 'auto'}; will-change: transform;`;
        }
    }"
    x-init="$watch('activeSlide', (a) => step(a))"
    {{-- السحب باللمس: اتجاه السحب يتبع اتجاه حركة البطاقات، ثم يُعاد تشغيل المؤقت --}}
    @touchstart.passive="sx = $event.touches[0].clientX"
    @touchend.passive="
        const dx = $event.changedTouches[0].clientX - sx;
        if (Math.abs(dx) > 40) {
            const rtl = getComputedStyle($el).direction === 'rtl';
            ((dx < 0) !== rtl) ? next() : previous();
            start();
        }
    "
    style="touch-action: pan-y;
           -webkit-mask-image: linear-gradient(to right, transparent 0, #000 clamp(1.5rem, 9%, 6rem), #000 calc(100% - clamp(1.5rem, 9%, 6rem)), transparent 100%);
           mask-image: linear-gradient(to right, transparent 0, #000 clamp(1.5rem, 9%, 6rem), #000 calc(100% - clamp(1.5rem, 9%, 6rem)), transparent 100%);"
    class="relative grid grid-cols-[minmax(0,1fr)] items-center overflow-hidden px-4 pb-10 pt-12 [--gap:1rem] sm:px-8 sm:[--gap:1.5rem] lg:px-2">

    <template x-for="i in m" :key="`card-${i}`">
        <article
            {{-- لكل بطاقة: رقمها j في الحلقة، وشريحتها الأصلية (النسخ تعيد استخدام الشرائح بالتناوب) --}}
            x-data="{ j: i - 1, orig: (i - 1) % n, slide: slides[(i - 1) % n] }"
            :style="cardStyle(j, getComputedStyle($el).direction === 'rtl')"
            @click="goTo(orig)"
            :aria-current="isActive(j) ? 'true' : 'false'"
            :aria-hidden="j >= n ? 'true' : 'false'"
            :class="isActive(j)
                ? 'shadow-[0_0_0_2px_rgba(217,179,107,0.9),0_20px_50px_-12px_rgba(122,22,32,0.45)]'
                : 'shadow-[0_12px_30px_-12px_rgba(74,13,18,0.35)]'"
            class="relative col-start-1 row-start-1 w-[78%] cursor-pointer justify-self-center rounded-[1.75rem] bg-[#FFF9EE] [--lift:0px] sm:w-[46%] lg:w-[27%] lg:[--lift:-0.75rem]">

            {{-- تاج صغير على البطاقة النشطة: left/translate فيزيائيان فيبقى في المنتصف تماماً بأي لغة --}}
            <span x-show="isActive(j)" x-transition.opacity aria-hidden="true"
                class="absolute -top-5 left-1/2 z-20 flex size-10 -translate-x-1/2 items-center justify-center rounded-full border-2 border-[#D9B36B] bg-[#FFF9EE] text-[#7A1620] shadow-md">
                @svg('lucide-crown', 'h-5 w-5', ['aria-hidden' => 'true'])
            </span>

            {{-- صورة الشريحة مع الشارة المائلة --}}
            <div class="relative h-44 overflow-hidden rounded-t-[1.75rem] bg-gradient-to-br from-[#7A1620] to-[#4A0D12] sm:h-52 lg:h-56">
                <img x-show="slide.image" :src="slide.image" :alt="slide.title" width="640" height="420"
                    loading="lazy" decoding="async"
                    class="h-full w-full object-cover transition-transform duration-700"
                    :class="isActive(j) ? 'scale-105' : 'scale-100'">
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-[#4A0D12]/35 via-transparent to-transparent"></div>

                <span x-show="slide.eyebrow" x-text="slide.eyebrow"
                    class="absolute start-3 top-3 max-w-[85%] rounded-2xl rounded-ss-lg bg-gradient-to-br from-[#7A1620] to-[#4A0D12] px-4 py-2 font-serif text-base font-bold leading-tight text-[#FFF9EE] shadow-lg ring-1 ring-[#D9B36B]/50 sm:text-xl"></span>
            </div>

            {{-- لوحة النص السفلية بحافة منحنية تتداخل مع الصورة --}}
            <div class="relative -mt-6 flex items-end justify-between gap-3 rounded-t-[1.75rem] bg-[#FFF9EE] px-5 pb-5 pt-5">
                <div class="min-w-0">

                    {{-- اليوم والوقت: يظهران على البطاقة النشطة إن وُجدا --}}
                    <div x-show="isActive(j) && (slide.day || slide.time)" x-transition.opacity
                        class="mb-2 flex flex-wrap items-center gap-x-3 gap-y-1 font-serif text-sm font-bold text-[#4A0D12]">
                        <span x-show="slide.day" class="flex items-center gap-1.5">
                            @svg('lucide-calendar-days', 'h-4 w-4 text-[#7A1620]', ['aria-hidden' => 'true'])
                            <span x-text="slide.day"></span>
                        </span>
                        <span x-show="slide.day && slide.time" aria-hidden="true" class="h-4 w-px bg-[#D9B36B]"></span>
                        <span x-show="slide.time" class="flex items-center gap-1.5" dir="ltr">
                            @svg('lucide-clock', 'h-4 w-4 text-[#7A1620]', ['aria-hidden' => 'true'])
                            <span x-text="slide.time"></span>
                        </span>
                    </div>

                    <h3 class="font-serif text-lg font-bold text-[#7A1620]" :class="isActive(j) ? '' : 'truncate'"
                        x-text="slide.title"></h3>
                    <p x-show="slide.text" x-text="slide.text" class="mt-1 text-sm text-[#4A0D12]/80"
                        :class="isActive(j) ? '' : 'line-clamp-2'"></p>
                </div>

                {{-- زر الرابط الدائري يظهر فقط إن وُجد رابط للشريحة --}}
                <a x-show="slide.url" :href="slide.url" :aria-label="slide.title" :tabindex="isActive(j) ? 0 : -1" @click.stop
                    class="flex size-11 shrink-0 items-center justify-center rounded-full bg-gradient-to-b from-[#7A1620] to-[#4A0D12] text-[#FFF9EE] shadow-lg transition hover:scale-105 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#D9B36B]">
                    @svg('lucide-chevron-right', 'h-5 w-5 rtl:rotate-180', ['aria-hidden' => 'true'])
                </a>
            </div>
        </article>
    </template>
</div>