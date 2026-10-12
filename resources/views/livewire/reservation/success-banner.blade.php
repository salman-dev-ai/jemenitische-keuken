{{--
لافتة النجاح. تظهر بعد إتمام الحجز برمز المرجع وملخص الموعد، وتدخل بحركة Alpine دون إعادة تحميل الصفحة.
--}}
@if ($successMessage)
    {{-- تشغّل حركة الظهور مرة واحدة عندما يدرج Livewire اللافتة في الصفحة. --}}
    <div x-data="{ shown: false }"
        x-init="$nextTick(() => { shown = true; $nextTick(() => $el.scrollIntoView({ behavior: 'smooth', block: 'nearest' })) })"
        x-show="shown" x-cloak
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="-translate-y-3 opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="-translate-y-3 opacity-0"
        class="mb-8 rounded-3xl bg-gradient-to-br from-[#6B1F2B] to-[#3E1F15] p-6 text-white shadow-xl sm:p-8">
        {{-- رأس التأكيد: الرسالة واسم الضيف ورمز المرجع بعد نجاح الإرسال. --}}
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
                <div class="font-mono text-base font-black tracking-widest text-amber-300">{{ $referenceCode }}</div>
            </div>
        </div>

        {{-- ملخص الموعد: التاريخ والوقت وعدد الضيوف كما ثُبّتوا في الحجز. --}}
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
