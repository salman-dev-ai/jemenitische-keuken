{{--
    هيكل عظمي لقسم المعرض: يطابق أبعاد وشبكة القسم الحقيقي تماما لمنع القفز،
    ويظهر عند أول تحميل للزائر فقط. يدعم RTL و LTR ويحترم تقليل الحركة.
--}}
<section id="gallery" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}" role="status" aria-busy="true"
    aria-live="polite"
    class="relative overflow-hidden bg-[#FBF5EA] pb-14 font-['Tajawal',sans-serif] sm:pb-20">

    {{-- نص للقارئات الصوتية فقط. --}}
    <span class="sr-only">{{ __('gallery.loading') }}</span>

    {{-- هيكل الصورة المميزة بنفس ارتفاعات القسم الحقيقي. --}}
    <div class="relative" aria-hidden="true">
        <div class="h-72 w-full animate-pulse bg-[#E6D9BF] motion-reduce:animate-none sm:h-96 lg:absolute lg:inset-0 lg:h-full">
        </div>

        <div
            class="pointer-events-none absolute inset-0 hidden from-[#FBF5EA] from-[28%] via-[#FBF5EA]/85 via-[40%] to-transparent to-[62%] lg:block ltr:bg-linear-to-r rtl:bg-linear-to-l">
        </div>

        {{-- هيكل الشارة العلوية. --}}
        <div class="absolute end-4 top-4 z-10 h-10 w-40 animate-pulse rounded-xl bg-[#4A0E0E]/25 motion-reduce:animate-none sm:end-8 sm:top-6">
        </div>

        {{-- هيكل الشعار والعنوان والوصف. --}}
        <div
            class="relative z-10 flex flex-col items-center gap-4 px-6 py-8 text-center sm:px-10 lg:min-h-[30rem] lg:w-5/12 lg:justify-center lg:py-12">
            <div class="h-24 w-24 animate-pulse rounded-full bg-[#4A0E0E]/30 motion-reduce:animate-none sm:h-28 sm:w-28">
            </div>
            <div class="h-12 w-64 max-w-full animate-pulse rounded-lg bg-[#4A0E0E]/20 motion-reduce:animate-none sm:h-14">
            </div>
            <div class="h-6 w-72 max-w-full animate-pulse rounded bg-[#B8862B]/30 motion-reduce:animate-none"></div>
            <div class="flex items-center justify-center gap-3">
                <span class="h-px w-16 bg-[#B8862B]/50"></span>
                <span class="h-2.5 w-2.5 rotate-45 bg-[#B8862B]/50"></span>
                <span class="h-px w-16 bg-[#B8862B]/50"></span>
            </div>
            <div class="w-full max-w-sm space-y-2">
                <div class="mx-auto h-6 w-40 animate-pulse rounded bg-[#4A0E0E]/20 motion-reduce:animate-none"></div>
                <div class="mx-auto h-4 w-full animate-pulse rounded bg-[#4A0E0E]/10 motion-reduce:animate-none"></div>
                <div class="mx-auto h-4 w-4/5 animate-pulse rounded bg-[#4A0E0E]/10 motion-reduce:animate-none"></div>
            </div>
        </div>
    </div>

    <div class="relative z-10 mx-auto max-w-7xl px-4 pt-8 sm:px-6 lg:px-8" aria-hidden="true">

        {{-- هيكل التبويبات. --}}
        <div class="mb-8 flex flex-wrap justify-center gap-2 sm:gap-3">
            <div class="h-11 w-24 animate-pulse rounded-full bg-[#4A0E0E]/30 motion-reduce:animate-none sm:w-32"></div>
            <div class="h-11 w-28 animate-pulse rounded-full bg-[#4A0E0E]/15 motion-reduce:animate-none sm:w-36"></div>
            <div class="h-11 w-24 animate-pulse rounded-full bg-[#4A0E0E]/15 motion-reduce:animate-none sm:w-32"></div>
            <div class="h-11 w-24 animate-pulse rounded-full bg-[#4A0E0E]/15 motion-reduce:animate-none sm:w-32"></div>
        </div>

        {{-- هيكل البطاقات بنفس شبكة 1 و2 و4 أعمدة. --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([0, 1, 2, 3] as $i)
                <div @class([
                    'relative flex h-[22rem] flex-col justify-end overflow-hidden rounded-2xl border border-[#B8862B]/30 bg-[#4A0E0E]/20 animate-pulse motion-reduce:animate-none',
                    'hidden sm:flex' => $i === 1,
                    'hidden lg:flex' => $i >= 2,
                ])>
                    <div class="absolute end-3 top-3 h-7 w-14 rounded-lg bg-[#FBF5EA]/30"></div>
                    <div class="space-y-2 p-5">
                        <div class="mx-auto h-6 w-2/3 rounded bg-[#FBF5EA]/40"></div>
                        <div class="mx-auto h-4 w-full rounded bg-[#FBF5EA]/25"></div>
                        <div class="mx-auto h-4 w-4/5 rounded bg-[#FBF5EA]/25"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
