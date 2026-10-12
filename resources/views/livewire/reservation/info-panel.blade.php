{{--
لوحة المعلومات الجانبية. تعرض مقدمة الحجز وتنبيه مدة الجلسة وبطاقات التاريخ وعدد الضيوف بجانب النموذج.
--}}
<div class="space-y-6 lg:pt-2">
    {{-- بطاقة تعريفية تعيد عنوان الحجز ووصفه المختصر بلون الخلفية الدافئ. --}}
    <div class="rounded-3xl border border-[#6B1F2B]/10 bg-[#F3E7DC] p-6 sm:p-8">
        <span class="mb-4 inline-flex rounded-full bg-white px-3 py-1 text-xs font-bold text-[#6B1F2B] shadow-sm">
            {{ __('messages.reservation.badge') }}
        </span>
        <h3 class="text-2xl font-black leading-relaxed text-[#32151C] sm:text-3xl">
            {{ __('messages.reservation.title') }}
        </h3>
        <p class="mt-3 text-sm leading-7 text-stone-700">
            {{ __('messages.reservation.subtitle') }}
        </p>
    </div>

    {{-- تنبيه مدة الجلسة داخل لوحة بلون المطعم إلى جانب تفاصيل الموعد. --}}
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

    {{-- بطاقتا التاريخ وعدد الضيوف وتعكسان القيم المختارة حالياً في النموذج. --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-1">
        <div class="flex items-start gap-4 rounded-2xl border border-stone-100 bg-white p-5 shadow-sm">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#FAF0E8] text-[#6B1F2B]">
                <x-lucide-calendar-check class="h-5 w-5" aria-hidden="true" />
            </div>
            <div>
                <h4 class="font-bold text-[#32151C]">{{ __('messages.reservation.reservationDate') }}</h4>
                <p class="mt-1 text-sm leading-6 text-stone-500">{{ __('messages.reservation.today') }} /
                    {{ __('messages.reservation.tomorrow') }}</p>
            </div>
        </div>

        <div class="flex items-start gap-4 rounded-2xl border border-stone-100 bg-white p-5 shadow-sm">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#FAF0E8] text-[#6B1F2B]">
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
