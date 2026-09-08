{{-- Skeleton placeholder for livewire/home-page --}}
<div
    class="space-y-0 font-['Tajawal',sans-serif]"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
    aria-busy="true"
    aria-label="{{ __('messages.accessibility.loading') ?? 'جاري التحميل...' }}"
>

    {{-- 1. Hero Section Skeleton --}}
    <section class="relative min-h-[92svh] flex items-center justify-center bg-gradient-to-b from-[#240B08] via-[#1D0806] to-[#2B0E0A] py-20 px-4 sm:px-6 lg:px-8 overflow-hidden border-b border-[#E07513]/30">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#E07513_1.5px,transparent_1.5px)] [background-size:24px_24px] pointer-events-none"></div>
        <div class="max-w-5xl mx-auto text-center relative z-10 space-y-7 w-full">
            {{-- Badge --}}
            <div class="flex justify-center">
                <div class="h-9 w-72 sm:w-96 rounded-full bg-white/10 animate-pulse"></div>
            </div>
            {{-- Title --}}
            <div class="space-y-3 flex flex-col items-center">
                <div class="h-12 sm:h-16 md:h-20 w-4/5 rounded-2xl bg-white/10 animate-pulse"></div>
                <div class="h-10 sm:h-14 md:h-16 w-3/4 rounded-2xl bg-[#E07513]/20 animate-pulse"></div>
            </div>
            {{-- Subtitle --}}
            <div class="flex flex-col items-center gap-2">
                <div class="h-4 w-3/5 rounded-full bg-white/10 animate-pulse"></div>
                <div class="h-4 w-2/5 rounded-full bg-white/10 animate-pulse"></div>
            </div>
            {{-- CTA Buttons --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-2">
                <div class="h-13 w-52 rounded-2xl bg-[#E07513]/30 animate-pulse"></div>
                <div class="h-13 w-44 rounded-2xl bg-white/10 animate-pulse"></div>
            </div>
            {{-- Stats Bar --}}
            <div class="mt-8 grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach (range(1, 4) as $i)
                    <div wire:key="stat-sk-{{ $i }}" class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-white/5 border border-white/10">
                        <div class="h-8 w-16 rounded-lg bg-white/15 animate-pulse"></div>
                        <div class="h-3 w-24 rounded-full bg-white/10 animate-pulse"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 2. Pillars Section Skeleton --}}
    <section class="relative bg-[#FAF4ED] py-20 px-4 sm:px-6 lg:px-8 border-b border-[#E07513]/20">
        <div class="max-w-7xl mx-auto">
            {{-- Section Header --}}
            <div class="text-center mb-12 space-y-3">
                <div class="flex justify-center">
                    <div class="h-7 w-40 rounded-full bg-stone-200 animate-pulse"></div>
                </div>
                <div class="flex justify-center">
                    <div class="h-10 w-3/4 max-w-xl rounded-2xl bg-stone-200 animate-pulse"></div>
                </div>
                <div class="flex justify-center">
                    <div class="h-4 w-1/2 rounded-full bg-stone-200 animate-pulse"></div>
                </div>
            </div>
            {{-- Pillars Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach (range(1, 4) as $i)
                    <div wire:key="pillar-sk-{{ $i }}" class="rounded-3xl bg-white border border-stone-100 p-6 space-y-4 shadow-sm">
                        <div class="h-14 w-14 rounded-2xl bg-stone-100 animate-pulse"></div>
                        <div class="h-5 w-3/4 rounded-lg bg-stone-100 animate-pulse"></div>
                        <div class="space-y-2">
                            <div class="h-3 w-full rounded-full bg-stone-100 animate-pulse"></div>
                            <div class="h-3 w-4/5 rounded-full bg-stone-100 animate-pulse"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 3. Signature Dishes Skeleton --}}
    <section class="relative bg-[#1C0907] py-20 px-4 sm:px-6 lg:px-8 border-b border-[#E07513]/30">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12 space-y-3">
                <div class="flex justify-center">
                    <div class="h-7 w-40 rounded-full bg-white/10 animate-pulse"></div>
                </div>
                <div class="flex justify-center">
                    <div class="h-10 w-2/3 rounded-2xl bg-white/10 animate-pulse"></div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach (range(1, 3) as $i)
                    <div wire:key="dish-sk-{{ $i }}" class="rounded-3xl bg-[#250B08] border border-[#E07513]/20 overflow-hidden">
                        <div class="h-56 bg-[#2E0E0A] animate-pulse"></div>
                        <div class="p-5 space-y-3">
                            <div class="h-5 w-3/4 rounded-lg bg-white/10 animate-pulse"></div>
                            <div class="h-3 w-full rounded-full bg-white/10 animate-pulse"></div>
                            <div class="h-3 w-2/3 rounded-full bg-white/10 animate-pulse"></div>
                            <div class="flex items-center justify-between pt-2">
                                <div class="h-6 w-16 rounded-lg bg-[#E07513]/20 animate-pulse"></div>
                                <div class="h-9 w-28 rounded-xl bg-[#E07513]/30 animate-pulse"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

</div>
