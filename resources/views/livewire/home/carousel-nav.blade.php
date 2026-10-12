{{--
    أدوات التنقل في السلايدر: زر السابق وزر التالي على الجانبين ومؤشرات نقاط في الأسفل. تستدعي دوال previous وnext وgoTo من Alpine.js وتعيد تشغيل المؤقت بعد كل نقرة. تتطلب متغيري isArabic وisDutch لنصوص إمكانية الوصول.
--}}

{{-- زر الشريحة السابقة يستدعي previous ثم يعيد تشغيل المؤقت التلقائي --}}
<button type="button" @click="previous(); start()"
    aria-label="{{ $isArabic ? 'الصورة السابقة' : ($isDutch ? 'Vorige dia' : 'Previous slide') }}"
    class="group absolute start-4 top-1/2 z-30 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-[#D9B36B]/40 bg-[#4A0D12]/40 text-[#D9B36B] shadow-lg backdrop-blur-md transition-all duration-300 hover:scale-105 hover:border-[#D9B36B] hover:bg-[#D9B36B] hover:text-[#4A0D12] sm:h-12 sm:w-12">
    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"
        aria-hidden="true">
        <path d="m15 18-6-6 6-6" />
    </svg>
</button>

{{-- زر الشريحة التالية يستدعي next ثم يعيد تشغيل المؤقت التلقائي --}}
<button type="button" @click="next(); start()"
    aria-label="{{ $isArabic ? 'الصورة التالية' : ($isDutch ? 'Volgende dia' : 'Next slide') }}"
    class="group absolute end-4 top-1/2 z-30 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-[#D9B36B]/40 bg-[#4A0D12]/40 text-[#D9B36B] shadow-lg backdrop-blur-md transition-all duration-300 hover:scale-105 hover:border-[#D9B36B] hover:bg-[#D9B36B] hover:text-[#4A0D12] sm:h-12 sm:w-12">
    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"
        aria-hidden="true">
        <path d="m9 18 6-6-6-6" />
    </svg>
</button>

{{-- مؤشرات النقاط: نقطة لكل شريحة والنقطة النشطة أطول وبلون متدرج --}}
<div class="absolute bottom-6 start-1/2 z-30 -translate-x-1/2" role="tablist"
    aria-label="{{ $isArabic ? 'شرائح الصور' : ($isDutch ? 'Afbeeldingsdia\'s' : 'Image slides') }}"
    dir="ltr">
    <div
        class="flex items-center gap-1.5 rounded-full border border-[#FDFBF7]/15 bg-[#2C0D0A]/55 px-3 py-2 shadow-[0_8px_30px_rgba(0,0,0,0.35)] backdrop-blur-xl">
        <template x-for="(slide, index) in slides" :key="`dot-${index}`">
            <button type="button" @click="goTo(index)"
                :aria-label="`{{ $isArabic ? 'انتقل إلى الصورة' : ($isDutch ? 'Ga naar dia' : 'Go to slide') }} ${index + 1}`"
                :aria-selected="activeSlide === index" role="tab"
                class="h-1.5 rounded-full transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#D9B36B]/70"
                :class="activeSlide === index ?
                    'w-8 bg-gradient-to-r from-[#D9B36B] to-[#E8C88A] shadow-[0_0_12px_rgba(217,179,107,0.55)]' :
                    'w-1.5 bg-[#FDFBF7]/45 hover:bg-[#FDFBF7]/80 hover:scale-125'">
            </button>
        </template>
    </div>
</div>
