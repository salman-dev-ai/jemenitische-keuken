{{-- resources/views/livewire/customer-reviews-marquee.blade.php --}}
<section id="reviews" class="py-20 bg-[#250B08] text-white relative overflow-hidden border-b border-[#E07513]/30 font-['Tajawal',sans-serif]" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-right">

        {{-- رأس القسم --}}
        <div class="text-center max-w-3xl mx-auto space-y-3 mb-12">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-amber-300 text-xs font-bold shadow-md">
                <span>⭐</span>
                <span>{{ __('messages.reviews.badge') ?? 'آراء وشهادات الضيوف' }}</span>
            </div>

            <h2 class="text-2xl sm:text-4xl md:text-5xl font-black text-white tracking-tight">
                {{ __('messages.reviews.title') ?? 'ماذا يقول عشاق المذاق اليماني في أوروبا' }}
            </h2>

            <div class="pt-2 flex items-center justify-center gap-2 text-xs font-bold text-amber-400">
                <span>⭐⭐⭐⭐⭐</span>
                <span class="text-stone-200">تقييم عام ممتاز 4.9 من 5 بناءً على أكثر من 1,450 تقييم موثق</span>
            </div>
        </div>

    </div>

    {{-- شريط الآراء المتحرك تلقائياً (Infinite Marquee) --}}
    <div class="relative w-full overflow-hidden py-4">
        <div class="absolute top-0 bottom-0 left-0 w-24 sm:w-40 bg-gradient-to-r from-[#250B08] to-transparent z-10 pointer-events-none"></div>
        <div class="absolute top-0 bottom-0 right-0 w-24 sm:w-40 bg-gradient-to-l from-[#250B08] to-transparent z-10 pointer-events-none"></div>

        <div class="flex gap-6 w-max animate-marquee hover:[animation-play-state:paused]">
            @foreach($reviews ?? [] as $rev)
                <div class="w-80 sm:w-96 bg-[#32110D]/90 backdrop-blur-md p-6 rounded-3xl border border-[#E07513]/30 shadow-xl flex flex-col justify-between text-right shrink-0 hover:border-[#FFD700] hover:scale-102 transition-all duration-300 select-none">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center text-amber-400 text-xs">⭐⭐⭐⭐⭐</div>
                            <span class="text-[11px] text-stone-400">{{ $rev['date'] }}</span>
                        </div>

                        <div class="mb-3">
                            <span class="text-[10px] font-bold text-amber-300 bg-[#E07513]/25 px-2.5 py-0.5 rounded-md border border-[#E07513]/40">
                                الطلب المفضل: {{ $rev['dish'] }}
                            </span>
                        </div>

                        <p class="text-xs sm:text-sm text-stone-200 leading-relaxed font-normal">
                            "{{ $rev['comment'] }}"
                        </p>
                    </div>

                    <div class="pt-4 mt-4 border-t border-white/10 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-full bg-[#E07513] text-white font-black text-xs flex items-center justify-center">
                                {{ mb_substr($rev['name'], 0, 1) }}
                            </div>
                            <div class="text-right">
                                <h4 class="text-xs font-extrabold text-white">{{ $rev['name'] }}</h4>
                                <p class="text-[10px] text-stone-400">{{ $rev['location'] }}</p>
                            </div>
                        </div>

                        <span class="text-[10px] font-bold text-emerald-400 bg-emerald-950/60 px-2 py-0.5 rounded-full border border-emerald-500/30">
                            ضيف موثق ✓
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
