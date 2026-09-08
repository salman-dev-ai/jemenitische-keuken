{{-- Skeleton placeholder for livewire/gallery-section --}}
<section
    class="relative overflow-hidden border-b border-[#E07513]/30 bg-[#1C0907] py-20 font-['Tajawal',sans-serif] text-white"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
    aria-busy="true"
    aria-label="{{ __('messages.accessibility.loading') ?? 'جاري التحميل...' }}"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="mb-10 text-center space-y-3">
            <div class="flex justify-center">
                <div class="h-7 w-36 rounded-full bg-white/10 animate-pulse"></div>
            </div>
            <div class="flex justify-center">
                <div class="h-10 w-3/4 max-w-xl rounded-2xl bg-white/10 animate-pulse"></div>
            </div>
            <div class="flex justify-center">
                <div class="h-4 w-1/2 max-w-sm rounded-full bg-white/10 animate-pulse"></div>
            </div>
        </div>

        {{-- Category Filter Tabs Skeleton --}}
        <div class="mb-8 flex flex-wrap justify-center gap-3">
            @foreach ([100, 80, 120, 90, 110] as $width)
                <div
                    class="h-10 rounded-full bg-white/10 animate-pulse"
                    style="width: {{ $width }}px"
                ></div>
            @endforeach
        </div>

        {{-- Gallery Grid Skeleton — Masonry-like with varied heights --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">

            {{-- Row 1 — tall, normal, tall --}}
            <div wire:key="gallery-sk-1" class="h-80 animate-pulse rounded-3xl bg-[#250B08] ring-1 ring-[#E07513]/20"></div>
            <div wire:key="gallery-sk-2" class="h-64 animate-pulse rounded-3xl bg-[#250B08] ring-1 ring-[#E07513]/20"></div>
            <div wire:key="gallery-sk-3" class="h-80 animate-pulse rounded-3xl bg-[#250B08] ring-1 ring-[#E07513]/20"></div>

            {{-- Row 2 — normal, wide (span 2), normal --}}
            <div wire:key="gallery-sk-4" class="h-64 animate-pulse rounded-3xl bg-[#250B08] ring-1 ring-[#E07513]/20 md:col-span-2 lg:col-span-1"></div>
            <div wire:key="gallery-sk-5" class="h-64 animate-pulse rounded-3xl bg-[#250B08] ring-1 ring-[#E07513]/20"></div>
            <div wire:key="gallery-sk-6" class="h-64 animate-pulse rounded-3xl bg-[#250B08] ring-1 ring-[#E07513]/20"></div>

        </div>

    </div>
</section>
