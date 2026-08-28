{{-- resources/views/livewire/home-page.blade.php --}}
<div class="space-y-0 text-right font-['Tajawal',sans-serif]" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- 1. HERO SECTION--}}
    <section id="home" class="relative min-h-[92vh] flex items-center justify-center bg-gradient-to-b from-[#240B08] via-[#1D0806] to-[#2B0E0A] text-white py-20 px-4 sm:px-6 lg:px-8 overflow-hidden border-b border-[#E07513]/30">

        {{-- خلفية جمالية ونقوش إسلامية --}}

        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#E07513_1.5px,transparent_1.5px)] [background-size:24px_24px] pointer-events-none"></div>
        <div class="absolute top-1/4 -right-24 w-96 h-96 bg-[#E07513]/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-12 -left-24 w-96 h-96 bg-[#B85709]/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-5xl mx-auto text-center relative z-10 space-y-7">

            {{-- بادج الترحيب التراثي --}}
            <div class="inline-flex items-center gap-2.5 px-5 py-2 rounded-full bg-white/10 backdrop-blur-md border border-[#E07513]/40 text-amber-300 text-xs sm:text-sm font-bold shadow-xl">
                  <x-lucide-sparkles class="w-4 h-4 text-amber-300" />
                <span>{{ __('messages.home.greetingBadge') }}</span>
            </div>

            {{-- العنوان الملكي البارز --}}
            <div class="space-y-2">
                <h1 class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-black tracking-tight leading-tight sm:leading-none text-white drop-shadow-lg">
                    <span>{{ __('messages.home.heroTitle') }}</span>
                    <br />
                    <span class="bg-gradient-to-r from-[#E07513] via-[#F6AA58] to-[#E07513] bg-clip-text text-transparent drop-shadow-sm font-extrabold">
                        {{ __('messages.brand.slogan')  }}
                    </span>
                </h1>
            </div>

            {{-- النص الوصفي   --}}
            <p class="text-stone-300 text-sm sm:text-lg max-w-3xl mx-auto leading-relaxed font-normal">
                {{ __('messages.home.heroSubtitle') }}
            </p>

            {{-- أزرار الإجراءات السريعة (CTA) --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-2">
                <a href="#reservation" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-[#E07513] via-[#E87E1C] to-[#B85709] hover:from-[#cb660a] hover:to-[#994303] text-white font-extrabold rounded-2xl shadow-xl shadow-[#E07513]/30 hover:shadow-2xl hover:shadow-[#E07513]/45 transition-all flex items-center justify-center gap-3 text-base hover:-translate-y-0.5">
                      <x-lucide-calendar-check class="w-5 h-5" />
                    <span>{{ __('messages.home.ctaReserve')  }}</span>
                </a>

                <a href="#menu_with_cart" class="w-full sm:w-auto px-8 py-4 bg-white/10 hover:bg-white/15 text-white font-bold rounded-2xl border border-white/25 hover:border-white/45 transition-all flex items-center justify-center gap-3 text-base backdrop-blur-md">
                    <x-lucide-utensils class="w-5 h-5" />
                    <span>{{ __('messages.home.ctaMenu')  }}</span>
                </a>
            </div>

            {{-- شريط الضيافة المجانية --}}
            <div class="pt-4 max-w-xl mx-auto">
                <div class="bg-[#E07513]/15 border border-[#E07513]/30 rounded-2xl py-2.5 px-4 flex items-center justify-center gap-2.5 text-xs text-amber-200 font-semibold shadow-inner">
                      <x-lucide-flame class="w-4 h-4" />
                    <span>{{ __('messages.home.hospitalityNote')   }}</span>
                </div>
            </div>

            {{-- إحصائيات المطعم --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-6 max-w-4xl mx-auto text-stone-200">
                <div class="bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 text-center">
                    <div class="text-2xl sm:text-3xl font-black text-[#E07513]">+25</div>
                    <div class="text-[11px] text-stone-300 font-medium mt-1" > <span>{{ __('messages.home.stats.years.label')  }}</span></div>
                </div>
                <div class="bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 text-center">
                    <div class="text-2xl sm:text-3xl font-black text-[#E07513]">100%</div>
                    <div class="text-[11px] text-stone-300 font-medium mt-1"><span>{{ __('messages.home.stats.halal.label')  }}</span></div>
                </div>
                <div class="bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 text-center">
                    <div class="text-2xl sm:text-3xl font-black text-[#E07513]">+18</div>
                    <div class="text-[11px] text-stone-300 font-medium mt-1"><span>{{ __('messages.home.stats.halal.label') }}</span> </div>
                </div>
                <div class="bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 text-center">
                    <div class="text-2xl sm:text-3xl font-black text-[#E07513]">4.9★</div>
                    <div class="text-[11px] text-stone-300 font-medium mt-1"><span>{{ __('messages.home.stats.rating.label')  }}</span></div>
                </div>
            </div>

        </div>
    </section>




</div>
