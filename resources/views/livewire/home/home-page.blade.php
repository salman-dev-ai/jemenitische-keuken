{{--
    الصفحة الرئيسية: تجمع أقسام الهوم عبر استدعاء الملفات الفرعية.
    لا تحتوي على الهيدر أو الفوتر، وتمرر المتغيرات المطلوبة مثل الشرائح وخيارات الطلب ولغة العرض إلى كل قسم.
--}}
<div id="home" dir="ltr"
    class="home-page relative overflow-hidden text-[#57151B] [background:#FBF7ED_center/cover_no-repeat_url('/images/home/home_logo.webp')]">
    {{-- طبقة تراكب دافئة لضمان وضوح النص فوق صورة الخلفية --}}
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 bg-[#FBF7ED]/70"></div>

    <div class="relative">

    {{-- قسم الهيرو: سلايدر تلقائي للصور مع النص المتحرك وأزرار التنقل والمؤشرات --}}
    @include('livewire.home.hero-carousel', [
        'slides' => $slides,
        'isArabic' => $isArabic,
        'isDutch' => $isDutch,
        'about' => $about ?? [],
    ])

    {{-- قسم خيارات الطلب: رأس القسم وبطاقات الطلب المولدة من البيانات --}}
    @include('livewire.home.order-options', [
        'orderOptions' => $orderOptions,
        'isArabic' => $isArabic,
        'isDutch' => $isDutch,
    ])

    {{-- زخرفة ختامية تظهر في أسفل الصفحة الرئيسية بعد قسم الطلب --}}
    <!-- @include('livewire.home.decoration') -->
    </div>
</div>
