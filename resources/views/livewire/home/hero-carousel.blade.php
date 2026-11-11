{{--
    سلايدر الهيرو الرئيسي بتصميم البطاقات المتحركة. يعرف حالة Alpine.js للشريحة النشطة والمؤقت والتنقل بالأسهم ولوحة المفاتيح،
    ثم يستدعي أجزاء الترويسة والبطاقات والتنقل، وبعدها قسم «من نحن» تحت البطاقات.
    يتوقف تلقائياً عند التمرير بالفأرة أو التركيز أو إخفاء التبويب أو خروج السلايدر من الشاشة، ولا يعمل التشغيل التلقائي لمن فعّل تقليل الحركة.
--}}

{{-- غلاف السلايدر: يربط حالة Alpine.js وأحداث الفأرة والتركيز ولوحة المفاتيح --}}
<section
    x-data="{
        /* حالة السلايدر: رقم الشريحة النشطة ومؤقت التشغيل التلقائي وقائمة الشرائح القادمة من Livewire */
        activeSlide: 0,
        timer: null,
        inView: true,
        reduceMotion: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
        slides: @js($slides),

        /* بدء التشغيل التلقائي كل ثلاث ثوان مع إلغاء أي مؤقت سابق */
        start() {
            this.pause();
            if (this.slides.length < 2 || !this.inView || this.reduceMotion) return;
            this.timer = setInterval(() => this.next(), 3000);
        },

        /* إيقاف المؤقت الحالي وتصفير مرجعه لمنع تراكم المؤقتات عند التنقل أو التركيز */
        pause() {
            if (this.timer) clearInterval(this.timer);
            this.timer = null;
        },

        /* الانتقال للشريحة التالية أو السابقة بشكل دائري دون تجاوز العدد */
        next() { this.activeSlide = (this.activeSlide + 1) % this.slides.length; },
        previous() { this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length; },

        /* الانتقال المباشر لشريحة محددة من المؤشرات أو النقر على البطاقة ثم إعادة تشغيل المؤقت من جديد */
        goTo(index) {
            this.activeSlide = index;
            this.start();
        },

        /* تمرير مسار البطاقات أفقياً ليتوسط البطاقة النشطة دون تحريك الصفحة رأسياً، ويعمل في RTL و LTR */
        centerActive(smooth = true) {
            const track = this.$refs.track;
            if (!track) return;
            const card = track.querySelectorAll(':scope > article')[this.activeSlide];
            if (!card) return;
            const t = track.getBoundingClientRect();
            const c = card.getBoundingClientRect();
            track.scrollBy({ left: (c.left + c.width / 2) - (t.left + t.width / 2), behavior: smooth ? 'smooth' : 'auto' });
        }
    }"
    x-init="
        start();
        $watch('activeSlide', () => centerActive());
        $nextTick(() => centerActive(false));
        new IntersectionObserver(([entry]) => {
            inView = entry.isIntersecting;
            inView ? start() : pause();
        }, { threshold: 0.2 }).observe($el);
    "
    @visibilitychange.document="document.hidden ? pause() : start()"
    @mouseenter="pause()"
    @mouseleave="start()"
    @focusin="pause()"
    @focusout="start()"
    @keydown.right.prevent="next()"
    @keydown.left.prevent="previous()"
    tabindex="0"
    aria-roledescription="carousel"
    aria-label="{{ $isArabic ? 'عروض المطعم' : ($isDutch ? 'Aanbiedingen van het restaurant' : 'Restaurant offers') }}"
    class="relative isolate overflow-hidden py-12 text-[#4A0D12] outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-[#D9B36B] lg:py-16">

    {{-- صورة الخلفية للهيرو بأكمله: عنصر img حقيقي ليكتشفه المتصفح مبكراً بدل خلفية CSS المتأخرة --}}
    <img src="{{ asset('images/home/home_logo.webp') }}" alt="" aria-hidden="true" width="1920" height="816"
        fetchpriority="high" decoding="async"
        class="pointer-events-none absolute inset-0 -z-20 h-full w-full object-cover object-center">

    {{-- طبقة تراكب دافئة لضمان وضوح البطاقات والنصوص فوق الصورة --}}
    <div aria-hidden="true"
        class="pointer-events-none absolute inset-0 -z-10 bg-[#FBF3E8]/5">
    </div>

    {{-- خلفية دافئة ناعمة خلف الترويسة --}}
    <div class="pointer-events-none absolute inset-0 -z-10 bg-[radial-gradient(ellipse_at_top,#FFF9EE,transparent_70%)]"></div>

    <div class="mx-auto max-w-7xl">

        {{-- الترويسة: شارة التاج والعنوان والوصف --}}
        @include('livewire.home.hero-content', [
            'isArabic' => $isArabic,
            'isDutch' => $isDutch,
        ])

        {{-- منطقة البطاقات مع الأسهم والنقاط، وهي المرجع لموضع الأسهم --}}
        <div class="relative mt-6 lg:px-14">

            {{-- مسار البطاقات: بطاقة لكل شريحة والنشطة منها مميزة --}}
            @include('livewire.home.hero-slides')

            {{-- أزرار السابق والتالي ومؤشرات النقاط التي تتيح للمستخدم التحكم بالسلايدر يدوياً --}}
            @include('livewire.home.carousel-nav', [
                'isArabic' => $isArabic,
                'isDutch' => $isDutch,
            ])
        </div>
    </div>
</section>

{{-- قسم «من نحن» تحت البطاقات: يقرأ $about إن كان متوفراً ولا يظهر شيئاً إن لم يكن --}}
@include('livewire.home.about-section', [
    'isArabic' => $isArabic,
    'isDutch' => $isDutch,
    'about' => $about,
])