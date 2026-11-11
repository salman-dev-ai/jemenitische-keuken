{{--
    أدوات التنقل في السلايدر: زر السابق وزر التالي على جانبي مسار البطاقات ومؤشرات نقاط تحته. تستدعي دوال previous وnext وgoTo من Alpine.js وتعيد تشغيل المؤقت بعد كل نقرة. تتطلب متغيري isArabic وisDutch لنصوص إمكانية الوصول.
    تُستدعى داخل غلاف relative في hero-carousel ليتموضع السهمان نسبة إليه.
--}}

{{-- زر الشريحة السابقة يستدعي previous ثم يعيد تشغيل المؤقت التلقائي --}}
<button type="button" @click="previous(); start()"
    aria-label="{{ $isArabic ? 'الصورة السابقة' : ($isDutch ? 'Vorige dia' : 'Previous slide') }}"
    class="group absolute start-2 top-[calc(50%-1.25rem)] z-30 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-[#D9B36B]/60 bg-[#4A0D12] text-[#D9B36B] shadow-lg transition-all duration-300 hover:scale-105 hover:border-[#D9B36B] hover:bg-[#D9B36B] hover:text-[#4A0D12] sm:h-12 sm:w-12 lg:start-3">
    @svg('lucide-chevron-left', 'w-6 h-6', ['aria-hidden' => 'true'])
</button>

{{-- زر الشريحة التالية يستدعي next ثم يعيد تشغيل المؤقت التلقائي --}}
<button type="button" @click="next(); start()"
    aria-label="{{ $isArabic ? 'الصورة التالية' : ($isDutch ? 'Volgende dia' : 'Next slide') }}"
    class="group absolute end-2 top-[calc(50%-1.25rem)] z-30 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-[#D9B36B]/60 bg-[#4A0D12] text-[#D9B36B] shadow-lg transition-all duration-300 hover:scale-105 hover:border-[#D9B36B] hover:bg-[#D9B36B] hover:text-[#4A0D12] sm:h-12 sm:w-12 lg:end-3">
    @svg('lucide-chevron-right', 'w-6 h-6', ['aria-hidden' => 'true'])
</button>

{{-- مؤشرات النقاط: نقطة لكل شريحة والنقطة النشطة أطول وبلون عنابي متدرج --}}
<div class="flex justify-center" role="tablist"
    aria-label="{{ $isArabic ? 'شرائح الصور' : ($isDutch ? 'Afbeeldingsdia\'s' : 'Image slides') }}"
    dir="ltr">
    <div
        class="flex items-center gap-1.5 rounded-full border border-[#D9B36B]/40 bg-[#4A0D12]/10 px-3 py-2 backdrop-blur-sm">
        <template x-for="(slide, index) in slides" :key="`dot-${index}`">
            <button type="button" @click="goTo(index)"
                :aria-label="`{{ $isArabic ? 'انتقل إلى الصورة' : ($isDutch ? 'Ga naar dia' : 'Go to slide') }} ${index + 1}`"
                :aria-selected="activeSlide === index" role="tab"
                class="h-1.5 rounded-full transition-all duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#D9B36B]/70"
                :class="activeSlide === index ?
                    'w-8 bg-gradient-to-r from-[#7A1620] to-[#4A0D12] shadow-[0_0_12px_rgba(122,22,32,0.4)]' :
                    'w-1.5 bg-[#D9B36B]/70 hover:bg-[#D9B36B] hover:scale-125'">
            </button>
        </template>
    </div>
</div>