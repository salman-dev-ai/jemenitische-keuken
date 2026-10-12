{{--
    بطاقة ساعات العمل.
    تعرض كل يوم وساعته من إعدادات المطعم داخل قائمة بارتفاع محدود
    يمكن تمريرها، أو رسالة بديلة عند غياب البيانات.
--}}
@php
    extract($footerLayout ?? [], EXTR_SKIP);
@endphp

<section
    class="max-h-60 min-h-[200px] rounded-[16px] border border-white/30 bg-[linear-gradient(135deg,rgba(79,57,59,.70),rgba(47,31,34,.76))] p-5 shadow-[0_12px_30px_rgba(0,0,0,.27)] backdrop-blur-[5px]">
    <h2 class="flex items-center gap-2 text-[15px] font-black text-white">
        <span class="h-2 w-2 rounded-full bg-[#e17a18] shadow-[0_0_10px_rgba(225,122,24,.85)]"></span>
        {{ $translate('messages.footer.hours', 'ساعات العمل') }}
    </h2>

    {{-- الأيام والساعات، والقائمة تُمرَّر داخل البطاقة --}}
    @if ($openingHours)
        <dl class="mt-5 max-h-36 space-y-1.5 overflow-y-auto pe-1 text-[12px] font-medium">
            @foreach ($openingHours as $day => $hours)
                @php
                    $hoursText = is_array($hours)
                        ? implode(
                            ' - ',
                            array_filter(array_map(static fn ($value): string => (string) $value, $hours)),
                        )
                        : (string) $hours;
                @endphp
                <div class="flex items-center justify-between gap-4">
                    <dt class="text-white/80">
                        {{ $translate('messages.days.' . strtolower((string) $day), (string) $day) }}
                    </dt>
                    <dd dir="ltr" class="font-bold text-[#efa452]">{{ $hoursText }}</dd>
                </div>
            @endforeach
        </dl>
    @else
        <p class="mt-5 text-[13px] leading-6 text-white/70">
            {{ $translate('messages.footer.hoursUnavailable', 'يرجى التواصل معنا لمعرفة ساعات العمل.') }}
        </p>
    @endif
</section>
