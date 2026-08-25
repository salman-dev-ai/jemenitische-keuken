{{-- resources/views/livewire/placeholders/gallery-section.blade.php --}}
<section
    class="relative overflow-hidden border-b border-[#E07513]/30 bg-[#1C0907] py-20 font-['Tajawal',sans-serif] text-white"
    dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}"
    aria-busy="true"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto mb-10 h-10 max-w-3xl animate-pulse rounded-full bg-[#E07513]/20"></div>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach(range(1, 6) as $placeholder)
                <div wire:key="gallery-placeholder-{{ $placeholder }}" class="h-80 animate-pulse rounded-3xl bg-[#250B08] ring-1 ring-[#E07513]/20"></div>
            @endforeach
        </div>
    </div>
</section>
