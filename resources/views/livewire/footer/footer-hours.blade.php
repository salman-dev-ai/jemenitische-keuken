{{--
    بطاقة ساعات العمل — كل يوم في سطر مستقل، وبداية/نهاية الدوام في شارتين منفصلتين.
    تقرأ $f['hours'] (صفوف: label / js / hours) وتتعامل مع:
      - صيغة الفورم الجديدة:  "9:30 ص - 11:00 م"  (أو AM/PM)
      - الصيغة القديمة 24 ساعة: "12:00 - 22:00"
    وتفصل الأيام المجمّعة إن وُجدت. اليوم الحالي يُميَّز عبر Alpine (الأحد = 0).
    في الهاتف: البطاقة قابلة للطي (مطوية افتراضياً) وشارة "مفتوح الآن" ظاهرة في العنوان.
    من 768px فأكثر: مفتوحة دائماً.
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

    // تحويل وقت منسّق (12 أو 24 ساعة) إلى دقائق منذ منتصف الليل
    $toMinutes = function (array $slot) use ($isMorning): ?int {
        if (! preg_match('/^(\d{1,2})(?::(\d{2}))?$/', $slot['time'], $m)) {
            return null;
        }

        $h = (int) $m[1];
        $min = (int) ($m[2] ?? 0);

        if ($slot['period'] !== null) {
            $h %= 12;
            if (! $isMorning($slot['period'])) {
                $h += 12;
            }
        }

        return $h * 60 + $min;
    };

    // هل المطعم مفتوح الآن؟ (حسب توقيت التطبيق، ويدعم الإغلاق بعد منتصف الليل)
    // توقيت المطعم (وليس توقيت الخادم). يمكن تغييره عبر config('restaurant.timezone')
    $now = now(config('restaurant.timezone', 'Europe/Amsterdam'));
    $nowMinutes = $now->hour * 60 + $now->minute;
    $todayIndex = $now->dayOfWeek; // الأحد = 0
    $isOpenNow = false;

    $todayRow = $dayRows->firstWhere('i', $todayIndex);
    if ($todayRow && $todayRow['hours']) {
        $p = explode(' - ', $todayRow['hours'], 2);
        if (count($p) === 2) {
            $start = $toMinutes($splitTime($p[0]));
            $end = $toMinutes($splitTime($p[1]));

            if ($start !== null && $end !== null) {
                if ($end <= $start) {
                    $end += 24 * 60;
                }
                $isOpenNow = $nowMinutes >= $start && $nowMinutes < $end;
            }
        }
    }
@endphp

<section
    x-data="{
        today: new Date().getDay(),
        desktop: false,
        open: false,
        init() {
            const mq = window.matchMedia('(min-width: 768px)');
            const sync = () => { this.desktop = mq.matches; if (mq.matches) this.open = true };
            sync();
            mq.addEventListener('change', sync);
        }
    }"
    class="overflow-hidden rounded-2xl border-2 border-[#9D4907]/40 bg-[#fbf3e8]/95 shadow-2xl backdrop-blur"
>
    <button
        type="button"
        @click="if (!desktop) open = !open"
        :aria-expanded="(open || desktop).toString()"
        aria-controls="opening-hours-panel"
        class="flex w-full items-center gap-3 bg-gradient-to-r from-[#1d4663] to-[#9D4907] px-5 py-4 text-start text-white focus-visible:outline-2 focus-visible:-outline-offset-4 focus-visible:outline-white sm:px-6 md:cursor-default"
    >
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10 ring-1 ring-white/20" aria-hidden="true">
            <x-lucide-clock class="h-5 w-5" />
        </span>

        <span class="grid flex-1 gap-1">
            <span class="text-sm font-black sm:text-base">{{ __('footer.hours') }}</span>

            @if ($dayRows->isNotEmpty())
                <span class="inline-flex w-fit items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-extrabold {{ $isOpenNow ? 'bg-[#d9f2e2] text-[#1b5c38]' : 'bg-[#fbe0dc] text-[#8a2a1f]' }}">
                    <span class="h-1.5 w-1.5 rounded-full {{ $isOpenNow ? 'animate-pulse bg-[#2f9e5f]' : 'bg-[#c5463a]' }}" aria-hidden="true"></span>
                    {{ $isOpenNow ? __('footer.open_now') : __('footer.closed_now') }}
                </span>
            @endif
        </span>

        <svg viewBox="0 0 24 24" aria-hidden="true"
            class="h-5 w-5 shrink-0 fill-none stroke-current transition-transform duration-300 md:hidden"
            :class="open ? 'rotate-180' : ''"
            stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m6 9 6 6 6-6" />
        </svg>
    </button>

    <div id="opening-hours-panel" x-show="open || desktop" x-collapse x-cloak class="p-4 sm:p-5">
        @if ($dayRows->isNotEmpty())
            <ul class="space-y-2 text-sm">
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
            <p class="text-sm leading-6 text-[#6a5444]">{{ __('footer.hours_unavailable') }}</p>
        @endif
    </div>
</section>
