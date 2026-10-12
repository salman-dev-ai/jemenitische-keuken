{{--
    الصفحة الرئيسية: تجمع أقسام الهوم عبر استدعاء الملفات الفرعية.
    لا تحتوي على الهيدر أو الفوتر، وتمرر المتغيرات المطلوبة مثل الشرائح وخيارات الطلب ولغة العرض إلى كل قسم.
--}}
<div id="home" dir="ltr" class="home-page overflow-hidden bg-[#FBF7ED] text-[#57151B]">

    {{-- قسم الهيرو: سلايدر تلقائي للصور مع النص المتحرك وأزرار التنقل والمؤشرات --}}
    @include('livewire.home.hero-carousel', [
        'slides' => $slides,
        'isArabic' => $isArabic,
        'isDutch' => $isDutch,
    ])

    {{-- قسم خيارات الطلب: رأس القسم وبطاقات الطلب المولدة من البيانات --}}
    @include('livewire.home.order-options', [
        'orderOptions' => $orderOptions,
        'isArabic' => $isArabic,
        'isDutch' => $isDutch,
    ])

    {{-- زخرفة ختامية تظهر في أسفل الصفحة الرئيسية بعد قسم الطلب --}}
    @include('livewire.home.decoration')
</div>
