{{--
    قسم خيارات الطلب: خلفية منقطة زخرفية ثم رأس القسم وشبكة بطاقات الطلب. يستقبل مصفوفة orderOptions ويمررها إلى ملف البطاقات، ويمرر متغيري اللغة لرأس القسم.
--}}

<section class="relative bg-[#FBF7ED] px-4 py-14 sm:px-6 sm:py-20 lg:px-8 lg:py-24">

    {{-- خلفية نقاط ذهبية خفيفة لا تتفاعل مع الماوس ومخفية عن قارئات الشاشة --}}
    <div aria-hidden="true"
        class="pointer-events-none absolute inset-0 opacity-[0.08] [background-image:radial-gradient(#C89B4A_1px,transparent_1px)] [background-size:36px_36px]">
    </div>

    <div class="relative mx-auto max-w-6xl">

        {{-- رأس القسم: العنوان والوصف بثلاث لغات مع الفاصل الزخرفي الذهبي --}}
        @include('livewire.home.order-header', [
            'isArabic' => $isArabic,
            'isDutch' => $isDutch,
        ])

        {{-- شبكة البطاقات تكرر بطاقة واحدة لكل عنصر من مصفوفة orderOptions بالترتيب --}}
        @include('livewire.home.order-cards', [
            'orderOptions' => $orderOptions,
            'isArabic' => $isArabic,
        ])
    </div>
</section>
