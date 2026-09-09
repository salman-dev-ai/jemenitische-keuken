{{-- resources/views/livewire/home-page.blade.php --}}
<div class="space-y-0 text-right font-['Tajawal',sans-serif]" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- HERO SECTION: نفس التصميم الأصلي مع خلفية صورة احترافية --}}
    <section id="home" class="relative min-h-[92svh] flex items-center justify-center bg-[#1D0806] text-white py-20 px-4 sm:px-6 lg:px-8 overflow-hidden border-b border-[#E07513]/30">

        {{-- صورة المطعم: ضع الصورة داخل storage/app/public/images/restaurant/ --}}
       <!-- alt="{{ __('messages.home.heroImageAlt') }}" -->
   <img
            src="{{asset('images/restaurant/restaurant-hero.webp') }}"
          

            alt="heome"
            width="1920"
            height="1280"
            fetchpriority="high"
            decoding="async"
            class="absolute inset-0 h-full w-full object-cover object-center"
        />
        {{-- طبقة تعتيم احترافية تحافظ على وضوح النص فوق الصورة --}}
        <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(24,8,5,.48)_0%,rgba(29,8,6,.78)_58%,rgba(20,6,4,.97)_100%)] pointer-events-none"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,transparent_5%,rgba(18,5,3,.45)_100%)] pointer-events-none"></div>

        {{-- نقوش إسلامية خفيفة فوق الصورة --}}
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#E07513_1.5px,transparent_1.5px)] [background-size:24px_24px] pointer-events-none"></div>
        <div class="absolute top-1/4 -right-24 w-96 h-96 bg-[#E07513]/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-12 -left-24 w-96 h-96 bg-[#B85709]/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-5xl mx-auto text-center relative z-10 space-y-7">

            {{-- بادج الترحيب التراثي --}}
            <div class="inline-flex items-center gap-2.5 px-5 py-2 rounded-full bg-black/25 backdrop-blur-md border border-[#E07513]/50 text-amber-300 text-xs sm:text-sm font-bold shadow-xl">
                <x-lucide-sparkles class="w-4 h-4 text-amber-300" aria-hidden="true" />
                <span>{{ __('messages.home.greetingBadge') }}</span>
            </div>

            {{-- العنوان الملكي البارز --}}
            <div class="space-y-2">
                <h1 class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-black tracking-tight leading-tight sm:leading-none text-white drop-shadow-[0_4px_18px_rgba(0,0,0,.7)]">
                    <span>{{ __('messages.home.heroTitle') }}</span>
                    <br />
                    <span class="bg-gradient-to-r from-[#E07513] via-[#F6AA58] to-[#E07513] bg-clip-text text-transparent drop-shadow-sm font-extrabold">
                        {{ __('messages.brand.slogan') }}
                    </span>
                </h1>
            </div>

            {{-- النص الوصفي --}}
            <p class="text-stone-200 text-sm sm:text-lg max-w-3xl mx-auto leading-relaxed font-normal drop-shadow-lg">
                {{ __('messages.home.heroSubtitle') }}
            </p>

            {{-- أزرار الإجراءات السريعة --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-2">
                <a href="#reservation" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-[#E07513] via-[#E87E1C] to-[#B85709] hover:from-[#cb660a] hover:to-[#994303] text-white font-extrabold rounded-2xl shadow-xl shadow-[#E07513]/30 hover:shadow-2xl hover:shadow-[#E07513]/45 transition-all flex items-center justify-center gap-3 text-base hover:-translate-y-0.5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFD700]">
                    <x-lucide-calendar-check class="w-5 h-5" aria-hidden="true" />
                    <span>{{ __('messages.home.ctaReserve') }}</span>
                </a>

                <a href="#menu_with_cart" class="w-full sm:w-auto px-8 py-4 bg-black/25 hover:bg-white/15 text-white font-bold rounded-2xl border border-white/30 hover:border-white/50 transition-all flex items-center justify-center gap-3 text-base backdrop-blur-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFD700]">
                    <x-lucide-utensils class="w-5 h-5" aria-hidden="true" />
                    <span>{{ __('messages.home.ctaMenu') }}</span>
                </a>
            </div>

            {{-- شريط الضيافة المجانية --}}
            <div class="pt-4 max-w-xl mx-auto">
                <div class="bg-black/25 border border-[#E07513]/40 rounded-2xl py-2.5 px-4 flex items-center justify-center gap-2.5 text-xs text-amber-200 font-semibold shadow-inner backdrop-blur-md">
                    <x-lucide-flame class="w-4 h-4" aria-hidden="true" />
                    <span>{{ __('messages.home.hospitalityNote') }}</span>
                </div>
            </div>

            {{-- إحصائيات المطعم --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-6 max-w-4xl mx-auto text-stone-200">
                <div class="bg-black/25 backdrop-blur-md p-4 rounded-2xl border border-white/15 text-center shadow-lg">
                    <div class="text-2xl sm:text-3xl font-black text-[#E07513]">+25</div>
                    <div class="text-[11px] text-stone-300 font-medium mt-1"><span>{{ __('messages.home.stats.years.label') }}</span></div>
                </div>
                <div class="bg-black/25 backdrop-blur-md p-4 rounded-2xl border border-white/15 text-center shadow-lg">
                    <div class="text-2xl sm:text-3xl font-black text-[#E07513]">100%</div>
                    <div class="text-[11px] text-stone-300 font-medium mt-1"><span>{{ __('messages.home.stats.halal.label') }}</span></div>
                </div>
                <div class="bg-black/25 backdrop-blur-md p-4 rounded-2xl border border-white/15 text-center shadow-lg">
                    <div class="text-2xl sm:text-3xl font-black text-[#E07513]">+18</div>
                    <div class="text-[11px] text-stone-300 font-medium mt-1"><span>{{ __('messages.home.stats.spices.label') }}</span></div>
                </div>
                <div class="bg-black/25 backdrop-blur-md p-4 rounded-2xl border border-white/15 text-center shadow-lg">
                    <div class="text-2xl sm:text-3xl font-black text-[#E07513]">4.9★</div>
                    <div class="text-[11px] text-stone-300 font-medium mt-1"><span>{{ __('messages.home.stats.rating.label') }}</span></div>
                </div>
            </div>

        </div>
    </section>

</div>
