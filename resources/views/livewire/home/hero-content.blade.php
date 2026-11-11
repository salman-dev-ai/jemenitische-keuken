{{--
    ترويسة قسم الهيرو: شارة تاج بين خطين ذهبيين ينتهيان بمعين، ثم العنوان والوصف.
    تتطلب متغيري isArabic وisDutch للنصوص، وهي ثابتة لا تتغير مع الشريحة النشطة.
    بيانات البطاقات نفسها تأتي من الشرائح في hero-slides.
--}}

@php
    $heading = $isArabic ? 'عروضنا' : ($isDutch ? 'Onze Aanbiedingen' : 'Our Offers');
    $title = $isArabic
        ? 'استفد من عروضنا الخاصة'
        : ($isDutch ? 'Profiteer van onze speciale aanbiedingen' : 'Enjoy our special offers');
    $subtitle = $isArabic
        ? 'استمتع بأطباق شهية بأسعار مغرية'
        : ($isDutch ? 'Geniet van heerlijke gerechten tegen aantrekkelijke prijzen' : 'Enjoy delicious dishes at attractive prices');
@endphp

<header class="px-4 text-center sm:px-8">
    {{-- شارة التاج بين خطين ذهبيين ينتهيان بمعين --}}
    <div class="mx-auto flex max-w-xl items-center justify-center gap-3">
        <span class="h-px flex-1 bg-gradient-to-l from-[#D9B36B] to-transparent"></span>
        <span aria-hidden="true" class="size-2 rotate-45 border border-[#D9B36B]"></span>
        <div class="relative">
            @svg('lucide-crown', 'absolute -top-4 start-1/2 h-5 w-5 -translate-x-1/2 text-[#D9B36B] ', ['aria-hidden' => 'true'])
            <span class="block rounded-full bg-gradient-to-b from-[#7A1620] to-[#4A0D12] px-8 py-2.5 font-serif text-lg font-bold text-[#FFF9EE] shadow-lg sm:px-10 sm:text-xl">
                {{ $heading }}
            </span>
        </div>
        <span aria-hidden="true" class="size-2 rotate-45 border border-[#D9B36B]"></span>
        <span class="h-px flex-1 bg-gradient-to-r from-[#D9B36B] to-transparent"></span>
    </div>

    {{-- العنوان الرئيسي والوصف القصير --}}
    <h2 class="mt-6 font-serif text-2xl font-bold text-[#7A1620] sm:text-4xl">{{ $title }}</h2>
    <p class="mt-2 text-sm text-[#4A0D12]/80 sm:text-base">{{ $subtitle }}</p>
</header>