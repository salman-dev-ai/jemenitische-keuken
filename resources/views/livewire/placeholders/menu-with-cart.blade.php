{{-- Skeleton placeholder for livewire/menu-with-cart --}}
<section
    class="relative overflow-hidden bg-[#FAF4ED] py-16 font-['Tajawal',sans-serif]"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
    aria-busy="true"
    aria-label="{{ __('messages.accessibility.loading') ?? 'جاري التحميل...' }}"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="mb-10 text-center space-y-3">
            <div class="flex justify-center">
                <div class="h-7 w-36 rounded-full bg-stone-200 animate-pulse"></div>
            </div>
            <div class="flex justify-center">
                <div class="h-10 w-2/3 max-w-lg rounded-2xl bg-stone-200 animate-pulse"></div>
            </div>
            <div class="flex justify-center">
                <div class="h-4 w-1/2 max-w-sm rounded-full bg-stone-200 animate-pulse"></div>
            </div>
        </div>

        {{-- Category Filter Tabs Skeleton --}}
        <div class="mb-8 flex gap-3 overflow-x-hidden" role="tablist" aria-label="Categories loading">
            @foreach (range(1, 6) as $i)
                <div
                    wire:key="cat-sk-{{ $i }}"
                    class="h-10 shrink-0 rounded-full bg-stone-200 animate-pulse"
                    style="width: {{ [88, 112, 96, 120, 80, 104][$i - 1] ?? 100 }}px"
                ></div>
            @endforeach
        </div>

        {{-- Dish Cards Grid Skeleton --}}
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach (range(1, 6) as $i)
                <div
                    wire:key="menu-sk-{{ $i }}"
                    class="overflow-hidden rounded-3xl bg-white border border-stone-100 shadow-sm"
                >
                    {{-- Dish Image Placeholder --}}
                    <div class="relative h-52 bg-stone-100 animate-pulse">
                        {{-- Chef Badge --}}
                        <div class="absolute top-3 {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }} h-6 w-24 rounded-full bg-stone-200 animate-pulse"></div>
                    </div>
                    {{-- Dish Info --}}
                    <div class="p-5 space-y-3">
                        <div class="h-5 w-3/4 rounded-lg bg-stone-200 animate-pulse"></div>
                        <div class="space-y-1.5">
                            <div class="h-3 w-full rounded-full bg-stone-100 animate-pulse"></div>
                            <div class="h-3 w-4/5 rounded-full bg-stone-100 animate-pulse"></div>
                        </div>
                        <div class="flex items-center justify-between pt-1 border-t border-stone-100">
                            <div class="h-7 w-16 rounded-xl bg-stone-200 animate-pulse"></div>
                            <div class="h-10 w-32 rounded-2xl bg-stone-200 animate-pulse"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    {{-- Floating Cart Bar Skeleton (mimics real bar position and size) --}}
    <div class="fixed bottom-5 inset-x-4 sm:inset-x-auto sm:right-6 sm:left-6 max-w-2xl mx-auto z-40 opacity-0 pointer-events-none" aria-hidden="true">
        <div class="bg-[#2A0D0A] p-3.5 rounded-3xl border-2 border-[#E07513]/30 flex items-center justify-between gap-3">
            <div class="h-11 w-11 rounded-2xl bg-white/10 animate-pulse shrink-0"></div>
            <div class="flex-1 space-y-1">
                <div class="h-3 w-32 rounded-full bg-white/10 animate-pulse"></div>
                <div class="h-5 w-24 rounded-lg bg-white/10 animate-pulse"></div>
            </div>
            <div class="h-10 w-36 rounded-2xl bg-[#E07513]/30 animate-pulse shrink-0"></div>
        </div>
    </div>

</section>
