{{--
    رأس قسم خيارات الطلب: فاصل زخرفي ذهبي وعنوان ووصف مترجمان حسب لغة الصفحة عربي أو هولندي أو إنجليزي. يعتمد على المتغيرين isArabic وisDutch ولا يحتاج إلى أي بيانات أخرى.
--}}

<div class="text-center">

    {{-- الفاصل الزخرفي: معين ذهبي بين خطين أفقيين فوق العنوان مباشرة --}}
    <div class="flex items-center justify-center gap-4 text-[#C89B4A]">
        <span class="h-px w-16 bg-[#C89B4A]"></span>
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
            aria-hidden="true">
            <path
                d="M2.7 10.3a2.41 2.41 0 0 0 0 3.41l7.59 7.59a2.41 2.41 0 0 0 3.41 0l7.59-7.59a2.41 2.41 0 0 0 0-3.41l-7.59-7.59a2.41 2.41 0 0 0-3.41 0Z" />
        </svg>
        <span class="h-px w-16 bg-[#C89B4A]"></span>
    </div>

    {{-- عنوان القسم يتغير حسب اللغة المحددة في المتغيرين isArabic وisDutch --}}
    <h2 class="mt-3 font-serif text-4xl font-bold tracking-[-0.04em] text-[#57151B] sm:text-5xl">
        {{ $isArabic ? 'خيارات الطلب' : ($isDutch ? 'Bestelopties' : 'Order Options') }}
    </h2>

    {{-- الوصف القصير الموجود تحت العنوان ويستخدم نفس آلية اختيار اللغة --}}
    <p class="mt-3 text-sm text-[#B5863C] sm:text-base">
        {{ $isArabic ? 'اختر الطريقة التي تناسبك لتجربة أطباقنا' : ($isDutch ? 'Kies hoe u van onze gerechten wilt genieten' : 'Choose how you want to experience our dishes') }}
    </p>
</div>
