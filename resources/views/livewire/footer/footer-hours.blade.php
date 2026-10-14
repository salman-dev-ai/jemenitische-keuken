{{--
    بطاقة ساعات العمل. تعرض الأيام المجمعة وتميز اليوم الحالي عبر Alpine،
    أو رسالة بديلة عند غياب البيانات.
--}}
{{-- بطاقة ساعات العمل مع تمييز اليوم الحالي --}}
<section class="rounded-2xl bg-[#fbf3e8]/95 p-5 shadow-lg backdrop-blur">
    <header class="flex items-center justify-between gap-2">
        <h3 class="text-base font-black text-[#2b1810]">{{ __('footer.hours') }}</h3>
        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#f0d3b0] text-[#c05a1c]">
            <x-lucide-clock class="h-5 w-5" />
        </span>
    </header>

    @if ($f['hours'])
        <dl class="mt-4 space-y-3 text-xs">
            @foreach ($f['hours'] as $row)
                <div class="rounded-lg px-2 py-1 transition"
                    :class="{{ \Illuminate\Support\Js::from($row['js']) }}.includes(today) ? 'bg-[#f0d3b0]/70' : ''">
                    <dt class="text-[#6a5444]">
                        {{ $row['label'] }}
                        <span x-show="{{ \Illuminate\Support\Js::from($row['js']) }}.includes(today)" x-cloak
                            class="ms-1 rounded bg-[#c05a1c] px-1.5 py-0.5 text-[10px] font-bold text-white">
                            {{ __('footer.today') }}
                        </span>
                    </dt>
                    @if ($row['hours'])
                        <dd dir="ltr" class="mt-0.5 text-start font-bold text-[#2b1810]" style="text-align: start">
                            {{ $row['hours'] }}
                        </dd>
                    @else
                        <dd class="mt-0.5 font-bold text-[#c0392b]">{{ __('footer.closed') }}</dd>
                    @endif
                </div>
            @endforeach
        </dl>
    @else
        <p class="mt-4 text-sm leading-6 text-[#6a5444]">{{ __('footer.hours_unavailable') }}</p>
    @endif
</section>
