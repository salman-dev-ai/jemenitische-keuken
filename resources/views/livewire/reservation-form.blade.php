<section class="relative overflow-hidden bg-[#FAF4ED] py-12 font-['Tajawal',sans-serif] sm:py-16 lg:py-20"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {{-- رأس القسم --}}
        <div class="mb-10 space-y-3 text-center sm:mb-12">
            <div
                class="inline-flex items-center gap-2 rounded-full border border-[#6B1F2B]/10 bg-white/70 px-4 py-2 text-xs font-bold text-[#6B1F2B] shadow-sm">
                <x-lucide-calendar-days class="h-4 w-4" aria-hidden="true" />
                <span>{{ __('messages.reservation.badge') }}</span>
            </div>

            <h2 class="mx-auto max-w-2xl text-3xl font-black tracking-tight text-[#32151C] sm:text-4xl">
                {{ __('messages.reservation.title') }}
            </h2>

            <p class="mx-auto max-w-xl text-sm leading-7 text-stone-600 sm:text-base">
                {{ __('messages.reservation.subtitle') }}
            </p>
        </div>

        {{-- بطاقة النجاح --}}
        @if ($successMessage)
            <div class="mb-8 rounded-3xl bg-gradient-to-br from-[#6B1F2B] to-[#3E1F15] p-6 text-white shadow-xl sm:p-8">
                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-white/10 pb-5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-500 shadow-lg">
                            <x-lucide-check-circle class="h-7 w-7" aria-hidden="true" />
                        </div>
                        <div>
                            <h4 class="font-black">{{ $successMessage }}</h4>
                            <p class="mt-1 text-xs text-stone-300">
                                {{ __('messages.reservation.welcome', ['name' => $customer_name]) }}
                            </p>
                        </div>
                    </div>

                    <div class="rounded-xl border border-white/15 bg-white/10 px-4 py-2 backdrop-blur-md">
                        <div class="text-[10px] text-stone-300">{{ __('messages.reservation.referenceCode') }}</div>
                        <div class="font-mono text-base font-black tracking-widest text-amber-300">{{ $referenceCode }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-5 text-xs text-stone-200 sm:grid-cols-4">
                    <div>
                        <span class="block text-[11px] text-stone-400">{{ __('messages.reservation.date') }}</span>
                        <span class="text-sm font-bold text-white">{{ $reservation_date }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] text-stone-400">{{ __('messages.reservation.time') }}</span>
                        <span class="text-sm font-bold text-white">{{ $reservation_time }}</span>
                    </div>
                    <div>
                        <span class="block text-[11px] text-stone-400">{{ __('messages.reservation.guests') }}</span>
                        <span class="text-sm font-bold text-white">
                            {{ __('messages.reservation.guestsCount', ['count' => $party_size]) }}
                        </span>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-2 lg:gap-10">
            {{-- بطاقة النموذج --}}
            <div class="relative overflow-visible rounded-3xl border border-stone-100 bg-white p-6 shadow-sm sm:p-8">
                <div
                    class="absolute inset-x-0 top-0 h-1.5 rounded-t-3xl bg-gradient-to-l from-[#6B1F2B] via-[#E07513] to-[#F3C892]">
                </div>

                @if ($errorMessage)
                    <div class="mb-6 flex items-start gap-3 rounded-2xl border-s-4 border-rose-500 bg-rose-50 p-4 text-sm text-rose-800"
                        role="alert">
                        <x-lucide-triangle-alert class="mt-0.5 h-5 w-5 shrink-0 text-rose-600" aria-hidden="true" />
                        <div>
                            <strong class="block font-bold">{{ __('messages.reservation.errorTitle') }}</strong>
                            <span>{{ $errorMessage }}</span>
                        </div>
                    </div>
                @endif

                <form wire:submit.prevent="submitReservation" class="space-y-7">
                    {{-- مؤشرات الخطوات --}}
                    <div class="flex items-center gap-3" aria-label="Reservation steps">
                        <div class="h-1.5 flex-1 rounded-full bg-[#6B1F2B]"></div>
                        <div class="h-1.5 flex-1 rounded-full bg-[#E07513]"></div>
                        <div class="h-1.5 flex-1 rounded-full bg-stone-100"></div>
                    </div>

                    {{-- الأشخاص والتاريخ والوقت --}}
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 text-xs font-black uppercase tracking-wider text-[#32151C]">
                            <span
                                class="flex h-6 w-6 items-center justify-center rounded-full bg-[#6B1F2B] text-[10px] text-white">1</span>
                            <span>{{ __('messages.reservation.step1') }}</span>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            {{-- قائمة عدد الأشخاص المخصصة --}}
                            <div x-data="{ open: false }" @keydown.escape.window="open = false"
                                class="relative z-30 sm:col-span-2">
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
                                    <x-lucide-chevron-down
                                        class="h-4 w-4 text-[#6B1F2B] transition-transform duration-200"
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
                                        <button type="button" wire:click="$set('party_size', {{ $size }})"
                                            @click="open = false"
                                            class="flex w-full items-center justify-between rounded-xl px-4 py-3 text-right text-sm transition {{ $party_size == $size ? 'bg-[#6B1F2B] font-bold text-white' : 'text-stone-700 hover:bg-[#FAF0E8] hover:text-[#6B1F2B]' }}">
                                            <span>{{ __('messages.reservation.guestsCount', ['count' => $size]) }}</span>
                                            @if ($party_size == $size)
                                                <x-lucide-check class="h-4 w-4" aria-hidden="true" />
                                            @endif
                                        </button>
                                    @endforeach
                                </div>

                                <input id="party-size-input" type="number" wire:model.live.debounce.300ms="party_size"
                                    min="1" max="20"
                                    class="mt-3 h-11 w-full rounded-xl border-stone-200 bg-white text-sm font-semibold focus:border-[#E07513] focus:ring-4 focus:ring-[#E07513]/10  p-3" />
                                @error('party_size')
                                    <span class="mt-1 block text-xs text-rose-500">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- التاريخ --}}
                            <div>
                                <label for="reservation-date-input" class="mb-2 block text-sm font-bold text-stone-700">
                                    {{ __('messages.reservation.reservationDate') }}
                                </label>
                                <div class="relative">
                                    <x-lucide-calendar-days
                                        class="pointer-events-none absolute start-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#6B1F2B]  p-3"
                                        aria-hidden="true" />
                                    <input id="reservation-date-input" type="date" wire:model.live="reservation_date"
                                        min="{{ now()->toDateString() }}"
                                        class="h-12 w-full rounded-xl border-stone-200 bg-[#FAF7F2] ps-11 text-sm font-semibold focus:border-[#E07513] focus:ring-4 focus:ring-[#E07513]/10  p-3" />
                                </div>

                                <div class="mt-2 flex gap-2 text-[11px]">
                                    <button type="button"
                                        wire:click="$set('reservation_date', '{{ now()->toDateString() }}')"
                                        class="flex-1 rounded-lg border border-stone-200 bg-white py-1.5 text-stone-600 transition hover:border-[#E07513] hover:text-[#6B1F2B]">{{ __('messages.reservation.today') }}</button>
                                    <button type="button"
                                        wire:click="$set('reservation_date', '{{ now()->addDay()->toDateString() }}')"
                                        class="flex-1 rounded-lg border border-stone-200 bg-white py-1.5 text-stone-600 transition hover:border-[#E07513] hover:text-[#6B1F2B]">{{ __('messages.reservation.tomorrow') }}</button>
                                </div>
                                @error('reservation_date')
                                    <span class="mt-1 block text-xs text-rose-500">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- الوقت --}}
                            <div>
                                <label for="reservation-time-input"
                                    class="mb-2 block text-sm font-bold text-stone-700">
                                    {{ __('messages.reservation.preferredTime') }}
                                </label>
                                <div class="relative">
                                    <x-lucide-clock
                                        class="pointer-events-none absolute start-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#6B1F2B]  p-3"
                                        aria-hidden="true" />
                                    <input id="reservation-time-input" type="time"
                                        wire:model.live="reservation_time"
                                        class="h-12 w-full rounded-xl border-stone-200 bg-[#FAF7F2] ps-11 text-sm font-semibold focus:border-[#E07513] focus:ring-4 focus:ring-[#E07513]/10 p-3" />
                                </div>
                                <span
                                    class="mt-2 block text-[11px] text-stone-500">{{ __('messages.reservation.sessionDuration') }}</span>
                                @error('reservation_time')
                                    <span class="mt-1 block text-xs text-rose-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-stone-100"></div>

                    {{-- بيانات العميل --}}
                    <div class="space-y-4">

                        <div
                            class="flex items-center gap-2 text-xs font-black uppercase tracking-wider text-[#32151C]">
                            <span
                                class="flex h-6 w-6 items-center justify-center rounded-full bg-[#6B1F2B] text-[10px] text-white">2</span>
                            <span>{{ __('messages.reservation.step3') }}</span>
                        </div>

                        <div class="grid grid-cols-1 gap-1 sm:grid-cols-2">
                            <div>
                                <label for="customer-name-input" class="mb-2 block text-sm font-bold text-stone-700">
                                    {{ __('messages.reservation.fullName') }} <span class="text-rose-500"
                                        aria-hidden="true">*</span>
                                </label>
                                <input id="customer-name-input" type="text" wire:model="customer_name"
                                    placeholder="{{ __('messages.reservation.fullNamePlaceholder') }}"
                                    autocomplete="name"
                                    class="h-12 w-full rounded-xl border-stone-200 bg-[#FAF7F2] text-sm focus:border-[#E07513] focus:ring-4 focus:ring-[#E07513]/10 p-3" />
                                @error('customer_name')
                                    <span class="mt-1 block text-xs text-rose-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1    ">
                                <div>
                                    <label for="customer-phone-input"
                                        class="mb-2 block text-sm font-bold text-stone-700">
                                        {{ __('messages.reservation.phone') }} <span class="text-rose-500"
                                            aria-hidden="true">*</span>
                                    </label>
                                    <input id="customer-phone-input" type="tel" wire:model="customer_phone"
                                        placeholder="+31 6 12 34 56 78" dir="ltr" autocomplete="tel"
                                        class="h-12 w-full rounded-xl border-stone-200 bg-[#FAF7F2] text-sm font-mono focus:border-[#E07513] focus:ring-4 focus:ring-[#E07513]/10 p-3" />
                                    @error('customer_phone')
                                        <span class="mt-1 block text-xs text-rose-500">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="special-requests-input" class="mb-2 block text-sm font-bold text-stone-700">
                                {{ __('messages.reservation.specialRequests') }}
                            </label>
                            <textarea id="special-requests-input" wire:model="special_requests" rows="3"
                                placeholder="{{ __('messages.reservation.specialRequestsPlaceholder') }}"
                                class="w-full rounded-xl border-stone-200 bg-[#FAF7F2] text-sm focus:border-[#E07513] focus:ring-4 focus:ring-[#E07513]/10"></textarea>
                        </div>
                    </div>

                    {{-- زر الإرسال --}}
                    <button type="submit" wire:loading.attr="disabled"
                        class="flex w-full items-center justify-center gap-3 rounded-2xl bg-[#6B1F2B] px-6 py-4 font-black text-white shadow-lg shadow-[#6B1F2B]/15 transition hover:bg-[#541721] hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-[#6B1F2B]/20 disabled:cursor-not-allowed disabled:opacity-50">
                        <span wire:loading.remove class="flex items-center gap-2">
                            <x-lucide-send class="h-4 w-4" aria-hidden="true" />
                            <span>{{ __('messages.reservation.submit') }}</span>
                        </span>
                        <span wire:loading class="flex items-center gap-2">
                            <svg class="h-5 w-5 animate-spin text-white" viewBox="0 0 24 24" fill="none"
                                aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span>{{ __('messages.reservation.processing') }}</span>
                        </span>
                    </button>
                </form>
            </div>

            {{-- لوحة المعلومات --}}
            <div class="space-y-6 lg:pt-2">
                <div class="rounded-3xl border border-[#6B1F2B]/10 bg-[#F3E7DC] p-6 sm:p-8">
                    <span
                        class="mb-4 inline-flex rounded-full bg-white px-3 py-1 text-xs font-bold text-[#6B1F2B] shadow-sm">
                        {{ __('messages.reservation.badge') }}
                    </span>
                    <h3 class="text-2xl font-black leading-relaxed text-[#32151C] sm:text-3xl">
                        {{ __('messages.reservation.title') }}
                    </h3>
                    <p class="mt-3 text-sm leading-7 text-stone-700">
                        {{ __('messages.reservation.subtitle') }}
                    </p>
                </div>

                <div class="rounded-3xl bg-[#6B1F2B] p-6 text-white shadow-xl sm:p-8">
                    <div class="mb-4 flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/10">
                            <x-lucide-info class="h-5 w-5 text-amber-300" aria-hidden="true" />
                        </div>
                        <h3 class="font-black text-amber-200">{{ __('messages.reservation.step1') }}</h3>
                    </div>
                    <p class="text-sm leading-7 text-stone-100">
                        {{ __('messages.reservation.sessionDuration') }}
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-1">
                    <div class="flex items-start gap-4 rounded-2xl border border-stone-100 bg-white p-5 shadow-sm">
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#FAF0E8] text-[#6B1F2B]">
                            <x-lucide-calendar-check class="h-5 w-5" aria-hidden="true" />
                        </div>
                        <div>
                            <h4 class="font-bold text-[#32151C]">{{ __('messages.reservation.reservationDate') }}</h4>
                            <p class="mt-1 text-sm leading-6 text-stone-500">{{ __('messages.reservation.today') }} /
                                {{ __('messages.reservation.tomorrow') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 rounded-2xl border border-stone-100 bg-white p-5 shadow-sm">
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#FAF0E8] text-[#6B1F2B]">
                            <x-lucide-users class="h-5 w-5" aria-hidden="true" />
                        </div>
                        <div>
                            <h4 class="font-bold text-[#32151C]">{{ __('messages.reservation.guests') }}</h4>
                            <p class="mt-1 text-sm leading-6 text-stone-500">
                                {{ __('messages.reservation.guestsCount', ['count' => $party_size]) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
