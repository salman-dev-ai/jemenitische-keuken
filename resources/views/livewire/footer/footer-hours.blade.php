{{--
    بطاقة ساعات العمل — كل يوم في سطر مستقل، وبداية/نهاية الدوام في شارتين منفصلتين.
    تقرأ $f['hours'] (صفوف: label / js / hours) وتتعامل مع:
      - صيغة الفورم الجديدة:  "9:30 ص - 11:00 م"  (أو AM/PM)
      - الصيغة القديمة 24 ساعة: "12:00 - 22:00"
    وتفصل الأيام المجمّعة إن وُجدت. اليوم الحالي يُميَّز عبر Alpine (الأحد = 0).
--}}
@php
    // فصل الأيام المجمّعة إلى صفوف مستقلة، مرتبة من الإثنين إلى الأحد
    $dayRows = collect($f['hours'] ?? [])
        ->flatMap(fn ($row) => collect($row['js'] ?? [])->map(fn ($i) => [
            'i'     => (int) $i,
            'label' => \Carbon\Carbon::now()
                ->startOfWeek(\Carbon\Carbon::SUNDAY)
                ->addDays((int) $i)
                ->locale(app()->getLocale())
                ->isoFormat('dddd'),
            'hours' => $row['hours'] ?? null,
        ]))
        ->unique('i')
        ->sortBy(fn ($d) => ($d['i'] + 6) % 7)
        ->values();

    // تقسيم "9:30 ص" إلى وقت + فترة (ص/م/AM/PM) للعرض المنسّق
    $splitTime = function (string $value): array {
        $value = trim($value);

        if (preg_match('/^(.+?)\s*(ص|م|AM|PM)$/ui', $value, $m)) {
            return ['time' => trim($m[1]), 'period' => $m[2]];
        }

        return ['time' => $value, 'period' => null];
    };

    // هل الفترة صباحية؟ (للتلوين: شمس/قمر)
    $isMorning = fn (?string $p): bool => $p !== null && in_array(mb_strtolower($p), ['ص', 'am'], true);
@endphp

<section x-data="{ today: new Date().getDay() }"
    class="rounded-2xl bg-[#fbf3e8]/95 p-5 shadow-lg backdrop-blur">
    <header class="flex items-center justify-between gap-2">
        <h3 class="text-base font-black text-[#2b1810]">{{ __('footer.hours') }}</h3>
        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#f0d3b0] text-[#c05a1c]">
            <x-lucide-clock class="h-5 w-5" />
        </span>
    </header>

    @if ($dayRows->isNotEmpty())
        <ul class="mt-4 space-y-2 text-sm">
            @foreach ($dayRows as $day)
                @php
                    $parts = $day['hours'] ? explode(' - ', $day['hours'], 2) : [];
                    $open  = isset($parts[0]) ? $splitTime($parts[0]) : null;
                    $close = isset($parts[1]) ? $splitTime($parts[1]) : null;
                @endphp

                <li class="flex flex-wrap items-center justify-between gap-x-3 gap-y-2 rounded-xl px-3 py-2.5 ring-1 ring-inset transition"
                    :class="today === {{ $day['i'] }}
                        ? 'bg-[#f0d3b0] ring-[#c05a1c]/40'
                        : 'bg-white/50 ring-[#e8d5bd]'">

                    {{-- اسم اليوم + شارة "اليوم" --}}
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-[#2b1810]">{{ $day['label'] }}</span>
                        <span x-show="today === {{ $day['i'] }}" x-cloak
                            class="rounded-md bg-[#c05a1c] px-1.5 py-0.5 text-[10px] font-bold text-white">
                            {{ __('footer.today') }}
                        </span>
                    </div>

                    @if ($open && $close)
                        {{-- بداية الدوام ← نهاية الدوام --}}
                        <div class="flex items-center gap-1.5">
                            @foreach ([$open, $close] as $slot)
                                <span dir="ltr"
                                    class="inline-flex items-baseline gap-1 rounded-lg bg-white px-2 py-1 font-bold tabular-nums text-[#2b1810] shadow-sm ring-1 ring-[#e8d5bd]">
                                    <span>{{ $slot['time'] }}</span>
                                    @if ($slot['period'])
                                        <span class="text-[10px] font-extrabold {{ $isMorning($slot['period']) ? 'text-[#d98a1c]' : 'text-[#5b4b9a]' }}">
                                            {{ $slot['period'] }}
                                        </span>
                                    @endif
                                </span>

                                @if ($loop->first)
                                    <x-lucide-arrow-right class="h-3.5 w-3.5 shrink-0 text-[#c05a1c] rtl:rotate-180" />
                                @endif
                            @endforeach
                        </div>
                    @else
                        <span class="rounded-md bg-[#c0392b]/10 px-2 py-0.5 text-xs font-bold text-[#c0392b]">
                            {{ __('footer.closed') }}
                        </span>
                    @endif
                </li>
            @endforeach
        </ul>
    @else
        <p class="mt-4 text-sm leading-6 text-[#6a5444]">{{ __('footer.hours_unavailable') }}</p>
    @endif
</section>
