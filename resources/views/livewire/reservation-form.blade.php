<div
    class="bg-white rounded-3xl p-6 md:p-10 shadow-2xl border border-[#D47716]/15 relative overflow-hidden font-sans"
    dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}"
    style="{{ app()->isLocale('ar') ? 'text-align: right;' : 'text-align: left;' }}"
>

    {{-- الشريط الجمالي العلوي --}}
    <div class="absolute top-0 right-0 left-0 h-1.5 bg-gradient-to-l from-[#D47716] via-[#E9963F] to-[#3E1F15]"></div>

    {{-- رأس النموذج والترحيب ومؤشر الطاولات المتوفرة --}}
    <div class="mb-8 border-b border-stone-100 pb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="w-8 h-8 rounded-lg bg-[#D47716]/10 text-[#D47716] flex items-center justify-center font-bold text-sm">
                    <x-lucide-calendar-days class="w-5 h-5" aria-hidden="true" />
                </span>
                <span class="text-xs font-bold text-[#D47716] tracking-wider uppercase">
                    {{ __('messages.reservation.badge') }}
                </span>
            </div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-[#3E1F15] tracking-tight">
                {{ __('messages.reservation.title') }}
            </h2>
            <p class="text-stone-500 text-sm mt-1">{{ __('messages.reservation.subtitle') }}</p>
        </div>
    </div>

    {{-- بطاقة النجاح الفندقية الرقمية --}}
    @if ($successMessage)
        <div class="bg-gradient-to-br from-[#3E1F15] to-[#24110B] text-white rounded-3xl p-6 md:p-8 shadow-2xl mb-8 relative overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-2xl font-bold shadow-lg">
                        <x-lucide-check-circle class="w-8 h-8" aria-hidden="true" />
                    </div>
                    <div>
                        <h4 class="font-extrabold text-lg text-white">{{ $successMessage }}</h4>
                        <p class="text-xs text-stone-300">
                            {{ __('messages.reservation.welcome', ['name' => $customer_name]) }}
                        </p>
                    </div>
                </div>

                {{-- الكود المرجعي للحجز --}}
                <div class="bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-xl border border-white/15">
                    <div class="text-[10px] text-stone-300">{{ __('messages.reservation.referenceCode') }}</div>
                    <div class="font-mono text-base font-black text-amber-300 tracking-widest">{{ $referenceCode }}</div>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 text-stone-200 text-xs">
                <div>
                    <span class="text-stone-400 block text-[11px]">{{ __('messages.reservation.date') }}</span>
                    <span class="font-bold text-white text-sm">{{ $reservation_date }}</span>
                </div>
                <div>
                    <span class="text-stone-400 block text-[11px]">{{ __('messages.reservation.time') }}</span>
                    <span class="font-bold text-white text-sm">{{ $reservation_time }}</span>
                </div>
                <div>
                    <span class="text-stone-400 block text-[11px]">{{ __('messages.reservation.guests') }}</span>
                    <span class="font-bold text-white text-sm">
                        {{ __('messages.reservation.guestsCount', ['count' => $party_size]) }}
                    </span>
                </div>
            </div>
        </div>
    @endif

    {{-- رسالة التنبيه في حالة وجود خطأ --}}
    @if ($errorMessage)
        <div class="bg-rose-50 border-s-4 border-rose-500 p-4 rounded-2xl mb-6 text-rose-800 text-sm flex items-center gap-3" role="alert">
            <span class="text-rose-600 text-xl font-bold">
                <x-lucide-triangle-alert class="w-5 h-5" aria-hidden="true" />
            </span>
            <div>
                <strong class="font-bold block">{{ __('messages.reservation.errorTitle') }}</strong>
                <span>{{ $errorMessage }}</span>
            </div>
        </div>
    @endif

    {{-- نموذج الحجز التفاعلي --}}
    <form wire:submit.prevent="submitReservation" class="space-y-8">

        {{-- الخطوة 1: الأشخاص والوقت --}}
        <div class="space-y-3">
            <div class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-[#3E1F15]">
                <span class="w-5 h-5 rounded-full bg-[#3E1F15] text-white flex items-center justify-center text-[10px]" aria-hidden="true">1</span>
                <span>{{ __('messages.reservation.step1') }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- عدد الضيوف مع أزرار سريعة --}}
                <div class="bg-[#FAF7F2] p-4 rounded-2xl border border-stone-200 space-y-2">
                    <div class="flex justify-between items-center text-xs font-bold text-stone-700">
                        <label for="party-size-input">{{ __('messages.reservation.partySize') }}</label>
                        <span class="text-[#D47716] font-black">
                            {{ __('messages.reservation.guestsCount', ['count' => $party_size]) }}
                        </span>
                    </div>

                    <div class="flex items-center gap-1" role="group" aria-label="{{ __('messages.reservation.partySize') }}">
                        @foreach([1, 2, 4, 6, 8] as $size)
                            <button type="button"
                                    wire:click="$set('party_size', {{ $size }})"
                                    aria-pressed="{{ $party_size == $size ? 'true' : 'false' }}"
                                    class="flex-1 py-1.5 text-xs font-bold rounded-lg transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#D47716] {{ $party_size == $size ? 'bg-[#D47716] text-white shadow-xs' : 'bg-white text-stone-700 hover:bg-stone-100 border border-stone-200' }}">
                                {{ $size }}
                            </button>
                        @endforeach
                    </div>

                    <input id="party-size-input" type="number" wire:model.live.debounce.300ms="party_size" min="1" max="20"
                           class="w-full bg-white rounded-xl border-stone-200 text-sm font-semibold focus:border-[#D47716] focus:ring-2 focus:ring-[#D47716]/20">
                    @error('party_size') <span class="text-xs text-rose-500 block">{{ $message }}</span> @enderror
                </div>

                {{-- التاريخ مع أزرار اختيار سريعة --}}
                <div class="bg-[#FAF7F2] p-4 rounded-2xl border border-stone-200 space-y-2">
                    <label for="reservation-date-input" class="block text-xs font-bold text-stone-700">
                        {{ __('messages.reservation.reservationDate') }}
                    </label>
                    <input id="reservation-date-input" type="date" wire:model.live="reservation_date"
                           min="{{ now()->toDateString() }}"
                           class="w-full bg-white rounded-xl border-stone-200 text-sm font-semibold focus:border-[#D47716] focus:ring-2 focus:ring-[#D47716]/20">
                    @error('reservation_date') <span class="text-xs text-rose-500 block">{{ $message }}</span> @enderror

                    <div class="flex gap-1 text-[11px] pt-1">
                        <button type="button" wire:click="$set('reservation_date', '{{ now()->toDateString() }}')"
                                class="flex-1 py-0.5 bg-white border rounded text-stone-600 hover:bg-stone-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#D47716]">
                            {{ __('messages.reservation.today') }}
                        </button>
                        <button type="button" wire:click="$set('reservation_date', '{{ now()->addDay()->toDateString() }}')"
                                class="flex-1 py-0.5 bg-white border rounded text-stone-600 hover:bg-stone-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#D47716]">
                            {{ __('messages.reservation.tomorrow') }}
                        </button>
                    </div>
                </div>

                {{-- وقت الحضور --}}
                <div class="bg-[#FAF7F2] p-4 rounded-2xl border border-stone-200 space-y-2">
                    <label for="reservation-time-input" class="block text-xs font-bold text-stone-700">
                        {{ __('messages.reservation.preferredTime') }}
                    </label>
                    <input id="reservation-time-input" type="time" wire:model.live="reservation_time"
                           class="w-full bg-white rounded-xl border-stone-200 text-sm font-semibold focus:border-[#D47716] focus:ring-2 focus:ring-[#D47716]/20">
                    @error('reservation_time') <span class="text-xs text-rose-500 block">{{ $message }}</span> @enderror
                    <span class="text-[11px] text-stone-500 block">{{ __('messages.reservation.sessionDuration') }}</span>
                </div>
            </div>
        </div>

        {{-- الخطوة 2: بيانات العميل والطلبات الخاصة --}}
        <div class="space-y-4 pt-4 border-t border-stone-100">
            <div class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-[#3E1F15]">
                <span class="w-5 h-5 rounded-full bg-[#3E1F15] text-white flex items-center justify-center text-[10px]" aria-hidden="true">2</span>
                <span>{{ __('messages.reservation.step3') }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="customer-name-input" class="block text-xs font-bold text-stone-700 mb-1.5">
                        {{ __('messages.reservation.fullName') }} <span class="text-rose-500" aria-hidden="true">*</span>
                    </label>
                    <input id="customer-name-input" type="text" wire:model="customer_name"
                           placeholder="{{ __('messages.reservation.fullNamePlaceholder') }}"
                           autocomplete="name"
                           class="w-full bg-white rounded-xl border-stone-200 text-sm focus:border-[#D47716] focus:ring-2 focus:ring-[#D47716]/20">
                    @error('customer_name') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="customer-phone-input" class="block text-xs font-bold text-stone-700 mb-1.5">
                        {{ __('messages.reservation.phone') }} <span class="text-rose-500" aria-hidden="true">*</span>
                    </label>
                    <input id="customer-phone-input" type="tel" wire:model="customer_phone"
                           placeholder="+31 6 12 34 56 78"
                           dir="ltr"
                           autocomplete="tel"
                           class="w-full bg-white rounded-xl border-stone-200 text-sm font-mono focus:border-[#D47716] focus:ring-2 focus:ring-[#D47716]/20">
                    @error('customer_phone') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label for="special-requests-input" class="block text-xs font-bold text-stone-700 mb-1.5">
                    {{ __('messages.reservation.specialRequests') }}
                </label>
                <textarea id="special-requests-input" wire:model="special_requests" rows="2"
                          placeholder="{{ __('messages.reservation.specialRequestsPlaceholder') }}"
                          class="w-full bg-white rounded-xl border-stone-200 text-sm focus:border-[#D47716] focus:ring-2 focus:ring-[#D47716]/20"></textarea>
            </div>
        </div>

        {{-- زر الإرسال مع مؤشر التحميل --}}
        <div class="pt-2">
            <button type="submit" wire:loading.attr="disabled"
                    class="w-full bg-gradient-to-r from-[#D47716] via-[#DE8325] to-[#B8630F] hover:from-[#c2680e] hover:to-[#9a4f08] text-white font-extrabold py-4 px-6 rounded-2xl shadow-xl shadow-[#D47716]/20 hover:shadow-2xl transition-all duration-300 flex items-center justify-center gap-3 cursor-pointer disabled:opacity-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#FFD700]">
                <span wire:loading.remove class="flex items-center gap-2">
                    <x-lucide-send class="w-4 h-4" aria-hidden="true" />
                    <span>{{ __('messages.reservation.submit') }}</span>
                </span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>{{ __('messages.reservation.processing') }}</span>
                </span>
            </button>
        </div>

    </form>
</div>
