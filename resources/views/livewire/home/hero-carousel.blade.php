{{--
    سلايدر الهيرو الرئيسي. يعرف حالة Alpine.js للشريحة النشطة والمؤقت والتنقل بالأسهم ولوحة المفاتيح، ثم يستدعي أجزاء الصور والنص وأزرار التنقل. يتوقف تلقائياً عند التمرير بالفأرة أو التركيز أو إخفاء التبويب.
--}}

{{-- غلاف السلايدر: يربط حالة Alpine.js وأحداث الفأرة والتركيز ولوحة المفاتيح --}}
<section
    x-data="{
        /* حالة السلايدر: رقم الشريحة النشطة ومؤقت التشغيل التلقائي وقائمة الشرائح القادمة من Livewire */
        activeSlide: 0,
        timer: null,
        slides: @js($slides),

        /* بدء التشغيل التلقائي كل ثلاث ثوان مع إلغاء أي مؤقت سابق */
        start() {
            this.pause();
            if (this.slides.length < 2) return;
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

        /* الانتقال المباشر لشريحة محددة من المؤشرات ثم إعادة تشغيل المؤقت من جديد */
        goTo(index) {
            this.activeSlide = index;
            this.start();
        }
    }"
    x-init="start()"
    @visibilitychange.document="document.hidden ? pause() : start()"
    @mouseenter="pause()"
    @mouseleave="start()"
    @focusin="pause()"
    @focusout="start()"
    @keydown.right.prevent="next()"
    @keydown.left.prevent="previous()"
    tabindex="0"
    aria-roledescription="carousel"
    aria-label="{{ $isArabic ? 'صور المطعم' : ($isDutch ? 'Restaurantfoto\'s' : 'Restaurant images') }}"
    class="relative isolate min-h-[560px] overflow-hidden bg-[#4A0D12] text-[#FFF9EE] outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-[#D9B36B] sm:min-h-[650px] lg:min-h-[calc(100svh-78px)] lg:max-h-[760px]">

    {{-- طبقة الصور: خلفية كل شريحة مع الانتقال والتدرج اللوني فوقها --}}
    @include('livewire.home.hero-slides')

    {{-- طبقة النص: عنوان ووصف كل شريحة يظهران مع الشريحة النشطة --}}
    @include('livewire.home.hero-content')

    {{-- أزرار السابق والتالي ومؤشرات النقاط التي تتيح للمستخدم التحكم بالسلايدر يدوياً --}}
    @include('livewire.home.carousel-nav', [
        'isArabic' => $isArabic,
        'isDutch' => $isDutch,
    ])
</section>
