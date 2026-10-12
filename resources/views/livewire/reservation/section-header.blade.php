{{--
رأس قسم الحجز. يعرض الشارة والعنوان والوصف في وسط الصفحة قبل بطاقة النموذج ولوحة المعلومات الجانبية.
--}}
{{-- عنوان القسم والوصف المختصر يظهران بمحاذاة وسطية أعلى منطقة الحجز. --}}
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
