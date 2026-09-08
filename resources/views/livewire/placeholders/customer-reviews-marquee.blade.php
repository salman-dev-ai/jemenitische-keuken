{{-- Skeleton placeholder for livewire/customer-reviews-marquee --}}
<section
    class="relative overflow-hidden bg-[#1C0907] py-16 font-['Tajawal',sans-serif] border-y border-[#E07513]/30"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
    aria-busy="true"
    aria-label="{{ __('messages.accessibility.loading') ?? 'جاري التحميل...' }}"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="mb-10 text-center space-y-3">
            <div class="flex justify-center">
                <div class="h-7 w-32 rounded-full bg-white/10 animate-pulse"></div>
            </div>
            <div class="flex justify-center">
                <div class="h-9 w-3/4 max-w-lg rounded-2xl bg-white/10 animate-pulse"></div>
            </div>
        </div>

        {{-- Marquee Strip Skeleton — 5 cards side-by-side --}}
        <div class="flex gap-5 overflow-x-hidden" aria-hidden="true">
            @foreach (range(1, 5) as $i)
                <div
                    wire:key="review-sk-{{ $i }}"
                    class="shrink-0 w-72 rounded-3xl bg-[#250B08] border border-[#E07513]/20 p-5 space-y-4"
                >
                    {{-- Header: Avatar + Name + Rating --}}
                    <div class="flex items-start gap-3">
                        <div class="h-11 w-11 shrink-0 rounded-full bg-white/10 animate-pulse"></div>
                        <div class="flex-1 space-y-2">
                            <div class="h-4 w-28 rounded-lg bg-white/10 animate-pulse"></div>
                            {{-- Stars --}}
                            <div class="flex gap-1">
                                @foreach (range(1, 5) as $star)
                                    <div class="h-3 w-3 rounded-sm bg-amber-500/30 animate-pulse"></div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    {{-- Review Text Lines --}}
                    <div class="space-y-2">
                        <div class="h-3 w-full rounded-full bg-white/10 animate-pulse"></div>
                        <div class="h-3 w-11/12 rounded-full bg-white/10 animate-pulse"></div>
                        <div class="h-3 w-4/5 rounded-full bg-white/10 animate-pulse"></div>
                    </div>
                    {{-- Footer: Dish Badge + Date --}}
                    <div class="flex items-center justify-between pt-2 border-t border-white/10">
                        <div class="h-6 w-24 rounded-full bg-[#E07513]/20 animate-pulse"></div>
                        <div class="h-4 w-16 rounded-full bg-white/10 animate-pulse"></div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>
