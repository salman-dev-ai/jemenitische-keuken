{{-- Skeleton placeholder for livewire/reservation-form --}}
<section
    class="relative overflow-hidden bg-[#FAF4ED] py-20 font-['Tajawal',sans-serif]"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
    aria-busy="true"
    aria-label="{{ __('messages.accessibility.loading') ?? 'جاري التحميل...' }}"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="mb-12 text-center space-y-3">
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

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">

            {{-- Form Card Skeleton --}}
            <div class="bg-white rounded-3xl border border-stone-100 shadow-sm p-6 sm:p-8 space-y-6">

                {{-- Step Indicators --}}
                <div class="flex items-center gap-3 mb-2">
                    @foreach (range(1, 3) as $step)
                        <div wire:key="step-sk-{{ $step }}" class="flex-1 flex flex-col gap-1">
                            <div class="h-1.5 w-full rounded-full {{ $step === 1 ? 'bg-[#E07513]/40 animate-pulse' : 'bg-stone-100 animate-pulse' }}"></div>
                        </div>
                    @endforeach
                </div>

                {{-- Party Size Row --}}
                <div class="space-y-2">
                    <div class="h-4 w-40 rounded-full bg-stone-200 animate-pulse"></div>
                    <div class="flex gap-3">
                        @foreach (range(1, 4) as $i)
                            <div wire:key="size-sk-{{ $i }}" class="h-14 flex-1 rounded-2xl bg-stone-100 animate-pulse"></div>
                        @endforeach
                    </div>
                </div>

                {{-- Date & Time Row --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <div class="h-4 w-28 rounded-full bg-stone-200 animate-pulse"></div>
                        <div class="h-12 w-full rounded-xl bg-stone-100 animate-pulse"></div>
                    </div>
                    <div class="space-y-2">
                        <div class="h-4 w-28 rounded-full bg-stone-200 animate-pulse"></div>
                        <div class="h-12 w-full rounded-xl bg-stone-100 animate-pulse"></div>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="border-t border-stone-100"></div>

                {{-- Name Field --}}
                <div class="space-y-2">
                    <div class="h-4 w-32 rounded-full bg-stone-200 animate-pulse"></div>
                    <div class="h-12 w-full rounded-xl bg-stone-100 animate-pulse"></div>
                </div>

                {{-- Phone & Email Row --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <div class="h-4 w-28 rounded-full bg-stone-200 animate-pulse"></div>
                        <div class="h-12 w-full rounded-xl bg-stone-100 animate-pulse"></div>
                    </div>
                    <div class="space-y-2">
                        <div class="h-4 w-28 rounded-full bg-stone-200 animate-pulse"></div>
                        <div class="h-12 w-full rounded-xl bg-stone-100 animate-pulse"></div>
                    </div>
                </div>

                {{-- Notes Textarea --}}
                <div class="space-y-2">
                    <div class="h-4 w-40 rounded-full bg-stone-200 animate-pulse"></div>
                    <div class="h-24 w-full rounded-xl bg-stone-100 animate-pulse"></div>
                </div>

                {{-- Submit Button --}}
                <div class="h-14 w-full rounded-2xl bg-[#E07513]/20 animate-pulse"></div>
            </div>

            {{-- Info Panel Skeleton --}}
            <div class="space-y-6">
                {{-- Info Cards --}}
                @foreach (range(1, 3) as $i)
                    <div wire:key="info-sk-{{ $i }}" class="bg-white rounded-2xl border border-stone-100 shadow-sm p-5 flex items-start gap-4">
                        <div class="h-12 w-12 shrink-0 rounded-2xl bg-stone-100 animate-pulse"></div>
                        <div class="flex-1 space-y-2">
                            <div class="h-4 w-2/5 rounded-lg bg-stone-200 animate-pulse"></div>
                            <div class="h-3 w-4/5 rounded-full bg-stone-100 animate-pulse"></div>
                            <div class="h-3 w-3/5 rounded-full bg-stone-100 animate-pulse"></div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</section>
