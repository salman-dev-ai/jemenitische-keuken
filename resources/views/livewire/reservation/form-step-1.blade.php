{{--
الخطوة الأولى. تجمع عدد الأشخاص وتاريخ الحجز والوقت المفضل، وتبقي خصائص Livewire مرتبطة حتى وهي مخفية.
--}}
{{-- تظهر عند الخطوة الأولى وتتلاشى مكانها دون إزالة حقول الربط من النموذج. --}}
<div x-show="step === 1" x-bind:aria-hidden="(step !== 1).toString()"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="translate-y-2 opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="translate-y-2 opacity-0"
    class="col-start-1 row-start-1 space-y-4">
    <div class="flex items-center gap-2 text-xs font-black uppercase tracking-wider text-[#32151C]">
        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#6B1F2B] text-[10px] text-white">1</span>
        <span>{{ __('messages.reservation.step1') }}</span>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        {{-- قائمة عدد الأشخاص مع إدخال رقمي مباشر مرتبط بخاصية حجم المجموعة. --}}
        <div x-data="{ open: false }" @keydown.escape.window="open = false" class="relative z-30 sm:col-span-2">
            <div class="mb-2 flex items-center justify-between">
                <label class="block text-sm font-bold text-stone-700">
                    {{ __('messages.reservation.partySize') }}
                </label>
                <span class="text-xs font-black text-[#E07513]">
                    {{ __('messages.reservation.guestsCount', ['count' => $party_size]) }}
                </span>
            </div>

            <button type="button" @click="open = !open" :aria-expanded="open.toString()"
                class="flex h-12 w-full items-center justify-between rounded-xl border border-stone-200 bg-[#FAF7F2] px-4 text-sm font-semibold text-stone-800 transition hover:border-[#E07513] focus:border-[#E07513] focus:outline-none focus:ring-4 focus:ring-[#E07513]/10">
                <span class="flex items-center gap-2">
                    <x-lucide-users class="h-4 w-4 text-[#6B1F2B]" aria-hidden="true" />
                    <span>{{ __('messages.reservation.guestsCount', ['count' => $party_size]) }}</span>
                </span>
                <x-lucide-chevron-down class="h-4 w-4 text-[#6B1F2B] transition-transform duration-200"
                    ::class="{ 'rotate-180': open }" aria-hidden="true" />
            </button>

            <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="translate-y-1 opacity-0"
                x-transition:enter-end="translate-y-0 opacity-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="translate-y-0 opacity-100"
                x-transition:leave-end="translate-y-1 opacity-0" @click.outside="open = false"
                class="absolute z-50 mt-2 w-full overflow-hidden rounded-2xl border border-stone-200 bg-white p-1.5 shadow-xl shadow-stone-900/10">
                @foreach ([1, 2, 4, 6, 8, 10, 12] as $size)
                    <button type="button" wire:click="$set('party_size', {{ $size }})" @click="open = false"
                        class="flex w-full items-center justify-between rounded-xl px-4 py-3 text-right text-sm transition {{ $party_size == $size ? 'bg-[#6B1F2B] font-bold text-white' : 'text-stone-700 hover:bg-[#FAF0E8] hover:text-[#6B1F2B]' }}">
                        <span>{{ __('messages.reservation.guestsCount', ['count' => $size]) }}</span>
                        @if ($party_size == $size)
                            <x-lucide-check class="h-4 w-4" aria-hidden="true" />
                        @endif
                    </button>
                @endforeach
            </div>

            <input id="party-size-input" type="number" wire:model.live.debounce.300ms="party_size" min="1" max="20"
                class="mt-3 h-11 w-full rounded-xl border-stone-200 bg-white text-sm font-semibold focus:border-[#E07513] focus:ring-4 focus:ring-[#E07513]/10 p-3" />
            @error('party_size')
                <span class="mt-1 block text-xs text-rose-500">{{ $message }}</span>
            @enderror
        </div>

        {{-- حقل التاريخ مع اختصار اليوم والغد وتحديث فوري لقيمة الموعد. --}}
        <div>
            <label for="reservation-date-input" class="mb-2 block text-sm font-bold text-stone-700">
                {{ __('messages.reservation.reservationDate') }}
            </label>
            <div class="relative">
                <x-lucide-calendar-days
                    class="pointer-events-none absolute start-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#6B1F2B] p-3"
                    aria-hidden="true" />
                <input id="reservation-date-input" type="date" wire:model.live="reservation_date"
                    min="{{ now()->toDateString() }}"
                    class="h-12 w-full rounded-xl border-stone-200 bg-[#FAF7F2] ps-11 text-sm font-semibold focus:border-[#E07513] focus:ring-4 focus:ring-[#E07513]/10 p-3" />
            </div>

            <div class="mt-2 flex gap-2 text-[11px]">
                <button type="button" wire:click="$set('reservation_date', '{{ now()->toDateString() }}')"
                    class="flex-1 rounded-lg border border-stone-200 bg-white py-1.5 text-stone-600 transition hover:border-[#E07513] hover:text-[#6B1F2B]">{{ __('messages.reservation.today') }}</button>
                <button type="button" wire:click="$set('reservation_date', '{{ now()->addDay()->toDateString() }}')"
                    class="flex-1 rounded-lg border border-stone-200 bg-white py-1.5 text-stone-600 transition hover:border-[#E07513] hover:text-[#6B1F2B]">{{ __('messages.reservation.tomorrow') }}</button>
            </div>
            @error('reservation_date')
                <span class="mt-1 block text-xs text-rose-500">{{ $message }}</span>
            @enderror
        </div>

        {{-- حقل الوقت المفضل مع ملاحظة مدة الجلسة وربط مباشر بالخاصية. --}}
        <div>
            <label for="reservation-time-input" class="mb-2 block text-sm font-bold text-stone-700">
                {{ __('messages.reservation.preferredTime') }}
            </label>
            <div class="relative">
                <x-lucide-clock
                    class="pointer-events-none absolute start-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#6B1F2B] p-3"
                    aria-hidden="true" />
                <input id="reservation-time-input" type="time" wire:model.live="reservation_time"
                    class="h-12 w-full rounded-xl border-stone-200 bg-[#FAF7F2] ps-11 text-sm font-semibold focus:border-[#E07513] focus:ring-4 focus:ring-[#E07513]/10 p-3" />
            </div>
            <span class="mt-2 block text-[11px] text-stone-500">{{ __('messages.reservation.sessionDuration') }}</span>
            @error('reservation_time')
                <span class="mt-1 block text-xs text-rose-500">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>
