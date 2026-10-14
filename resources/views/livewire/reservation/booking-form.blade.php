{{--
بطاقة نموذج الحجز. تدير انتقال الخطوتين عبر Alpine، وتعرض خطأ الخادم، وتستدعي الخطوة الأولى والثانية مع الإرسال.
--}}
@php
    $reservationStepHint = null;
    $stepOneFailed = $errors->has('party_size') || $errors->has('reservation_date') || $errors->has('reservation_time');
    $stepTwoFailed = $errors->has('customer_name') || $errors->has('customer_phone') || $errors->has('special_requests');

    if ($stepOneFailed) {
        $reservationStepHint = 1;
    } elseif ($stepTwoFailed) {
        $reservationStepHint = 2;
    }
@endphp

{{-- جذر التفاعل: رقم الخطوة يبقى على المتصفح ولا يُرسل مع طلب Livewire. --}}
<div wire:key="reservation-step-hint-{{ $reservationStepHint ?? 0 }}"
    x-data="{ step: {{ $reservationStepHint ?? 1 }} }"
    class="relative overflow-visible rounded-3xl border border-stone-100 bg-white p-6 shadow-sm sm:p-8">
    {{-- شريط التدرج العلوي يميز بطاقة النموذج عن خلفية الصفحة الفاتحة. --}}
    <div class="absolute inset-x-0 top-0 h-1.5 rounded-t-3xl bg-gradient-to-l from-[#6B1F2B] via-[#E07513] to-[#F3C892]">
    </div>

    @if ($errorMessage)
        {{-- تنبيه عام بفشل الإرسال كما أعاده مكوّن الحجز من الخادم. --}}
        <div class="mb-6 flex items-start gap-3 rounded-2xl border-s-4 border-rose-500 bg-rose-50 p-4 text-sm text-rose-800"
            role="alert">
            <x-lucide-triangle-alert class="mt-0.5 h-5 w-5 shrink-0 text-rose-600" aria-hidden="true" />
            <div>
                <strong class="block font-bold">{{ __('messages.reservation.errorTitle') }}</strong>
                <span>{{ $errorMessage }}</span>
            </div>
        </div>
    @endif

    {{--
        الإرسال يبقى هنا حتى ترث الحقول الفرعية نفس مكوّن Livewire.

        إرسال الحجز + فتح واتساب في تبويب جديد:
        1) عند الضغط نفتح تبويباً فارغاً فوراً (داخل حدث المستخدم) كي لا يحجبه المتصفح.
        2) ننتظر نتيجة submitReservation() من الخادم: رابط wa.me عند النجاح أو null عند أي فشل.
        3) نجاح ⇒ نوجّه التبويب إلى واتساب. فشل ⇒ نغلق التبويب الفارغ ويبقى العميل في صفحته.
        4) إن حجب المتصفح التبويب نفتح واتساب في الصفحة الحالية كحل بديل.
    --}}
    <form
        x-data="{
            async submit() {
                const tab = window.open('', '_blank');
                let url = null;

                try {
                    url = await this.$wire.submitReservation();
                } finally {
                    const isValid = typeof url === 'string' && url.startsWith('https://wa.me/');

                    if (isValid && tab && !tab.closed) {
                        tab.opener = null;
                        tab.location.href = url;
                    } else if (isValid) {
                        window.location.href = url;
                    } else if (tab && !tab.closed) {
                        tab.close();
                    }
                }
            },
        }"
        @submit.prevent="submit()"
        class="space-y-7">
        {{-- يلوّن مؤشر التقدم حسب الخطوة الظاهرة الآن أمام المستخدم داخل النموذج. --}}
        <div class="flex items-center gap-3" aria-label="Reservation steps">
            <div class="h-1.5 flex-1 rounded-full transition-colors duration-300"
                :class="step >= 1 ? 'bg-[#6B1F2B]' : 'bg-stone-100'"></div>
            <div class="h-1.5 flex-1 rounded-full transition-colors duration-300"
                :class="step >= 2 ? 'bg-[#E07513]' : 'bg-stone-100'"></div>
            <div class="h-1.5 flex-1 rounded-full bg-stone-100"></div>
        </div>

        {{-- تراكب الخطوتين في خلية واحدة حتى لا يقفز ارتفاع البطاقة أثناء الانتقال. --}}
        <div class="grid grid-cols-1">
            @include('livewire.reservation.form-step-1')
            @include('livewire.reservation.form-step-2')
        </div>

        {{-- أزرار الرجوع والمتابعة والإرسال مع إظهار حالة المعالجة أثناء الطلب. --}}
        <div class="flex items-center gap-3">
            <button type="button" x-show="step === 2" x-cloak @click="step = 1"
                class="flex h-14 shrink-0 items-center justify-center rounded-2xl border border-stone-200 bg-white px-6 font-black text-[#6B1F2B] transition hover:border-[#E07513] hover:bg-[#FAF7F2] focus:outline-none focus:ring-4 focus:ring-[#E07513]/15">
                {{ app()->getLocale() === 'ar' ? 'السابق' : 'Back' }}
            </button>

            <button type="button" x-show="step === 1" @click="step = 2"
                class="flex w-full flex-1 items-center justify-center gap-3 rounded-2xl bg-[#6B1F2B] px-6 py-4 font-black text-white shadow-lg shadow-[#6B1F2B]/15 transition hover:bg-[#541721] hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-[#6B1F2B]/20">
                {{ app()->getLocale() === 'ar' ? 'متابعة' : 'Continue' }}
            </button>

            <button type="submit" x-show="step === 2" x-cloak wire:loading.attr="disabled"
                class="flex w-full flex-1 items-center justify-center gap-3 rounded-2xl bg-[#6B1F2B] px-6 py-4 font-black text-white shadow-lg shadow-[#6B1F2B]/15 transition hover:bg-[#541721] hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-[#6B1F2B]/20 disabled:cursor-not-allowed disabled:opacity-50">
                <span wire:loading.remove class="flex items-center gap-2">
                    <x-lucide-send class="h-4 w-4" aria-hidden="true" />
                    <span>{{ __('messages.reservation.submit') }}</span>
                </span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="h-5 w-5 animate-spin text-white" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>{{ __('messages.reservation.processing') }}</span>
                </span>
            </button>
        </div>
    </form>
</div>