{{-- Skeleton placeholder for livewire/reservation-form --}}
<section class="relative overflow-hidden bg-[#FAF4ED] py-12 font-['Tajawal',sans-serif] sm:py-16 lg:py-20"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" aria-busy="true"
    aria-label="{{ __('messages.accessibility.loading', [], app()->getLocale()) }}">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Section Header --}}
        {{-- رأس القسم: مطابق للرأس الفعلي --}}
        <div class="mb-10 space-y-3 text-center sm:mb-12" aria-hidden="true">
            <div class="flex justify-center">
                <div class="h-9 w-40 animate-pulse rounded-full bg-stone-200"></div>
            </div>
            <div class="flex justify-center">
                <div class="h-10 w-full max-w-2xl animate-pulse rounded-2xl bg-stone-200 sm:h-12"></div>
            </div>
            <div class="flex justify-center">
                <div class="h-4 w-11/12 max-w-xl animate-pulse rounded-full bg-stone-200"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-2 lg:gap-10">
            {{-- بطاقة النموذج --}}
            <div class="relative overflow-hidden rounded-3xl border border-stone-100 bg-white p-6 shadow-sm sm:p-8"
                aria-hidden="true">
                <div class="absolute inset-x-0 top-0 h-1.5 rounded-t-3xl bg-stone-200"></div>

                {{-- شريط الخطوات: 3 أجزاء كما في النموذج الفعلي --}}
                <div class="mb-7 flex items-center gap-3">
                    @foreach ([1, 2, 3] as $step)
                        <div wire:key="reservation-form-skeleton-step-{{ $step }}"
                            class="h-1.5 flex-1 animate-pulse rounded-full {{ $step <= 2 ? 'bg-[#E07513]/30' : 'bg-stone-100' }}">
                        </div>
                    @endforeach
                </div>

                <div class="space-y-7">
                    {{-- الخطوة الأولى --}}
                    <div class="space-y-4">
                        <div class="flex items-center gap-2">
                            <div class="h-6 w-6 animate-pulse rounded-full bg-stone-200"></div>
                            <div class="h-4 w-32 animate-pulse rounded-full bg-stone-200"></div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            {{-- قائمة عدد الأشخاص + حقل العدد اليدوي --}}
                            <div class="space-y-2 sm:col-span-2">
                                <div class="flex items-center justify-between">
                                    <div class="h-4 w-32 animate-pulse rounded-full bg-stone-200"></div>
                                    <div class="h-3 w-20 animate-pulse rounded-full bg-stone-100"></div>
                                </div>
                                <div class="h-12 w-full animate-pulse rounded-xl bg-stone-100"></div>
                                <div class="h-11 w-full animate-pulse rounded-xl bg-stone-100"></div>
                            </div>

                            {{-- التاريخ --}}
                            <div class="space-y-2">
                                <div class="h-4 w-28 animate-pulse rounded-full bg-stone-200"></div>
                                <div class="h-12 w-full animate-pulse rounded-xl bg-stone-100"></div>
                                <div class="flex gap-2">
                                    <div class="h-7 flex-1 animate-pulse rounded-lg bg-stone-100"></div>
                                    <div class="h-7 flex-1 animate-pulse rounded-lg bg-stone-100"></div>
                                </div>
                            </div>

                            {{-- الوقت --}}
                            <div class="space-y-2">
                                <div class="h-4 w-28 animate-pulse rounded-full bg-stone-200"></div>
                                <div class="h-12 w-full animate-pulse rounded-xl bg-stone-100"></div>
                                <div class="h-3 w-36 animate-pulse rounded-full bg-stone-100"></div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-stone-100"></div>

                    {{-- الخطوة الثانية --}}
                    <div class="space-y-4">
                        <div class="flex items-center gap-2">
                            <div class="h-6 w-6 animate-pulse rounded-full bg-stone-200"></div>
                            <div class="h-4 w-40 animate-pulse rounded-full bg-stone-200"></div>
                        </div>

                        {{-- الاسم: عرض كامل كما في النموذج الفعلي --}}
                        <div class="space-y-2">
                            <div class="h-4 w-32 animate-pulse rounded-full bg-stone-200"></div>
                            <div class="h-12 w-full animate-pulse rounded-xl bg-stone-100"></div>
                        </div>

                        {{-- الهاتف: صف واحد في النموذج الحالي --}}
                        <div class="space-y-2">
                            <div class="h-4 w-28 animate-pulse rounded-full bg-stone-200"></div>
                            <div class="h-12 w-full animate-pulse rounded-xl bg-stone-100"></div>
                        </div>

                        {{-- الملاحظات --}}
                        <div class="space-y-2">
                            <div class="h-4 w-40 animate-pulse rounded-full bg-stone-200"></div>
                            <div class="h-24 w-full animate-pulse rounded-xl bg-stone-100"></div>
                        </div>
                    </div>

                    {{-- زر الإرسال --}}
                    <div class="h-14 w-full animate-pulse rounded-2xl bg-[#6B1F2B]/20"></div>
                </div>
            </div>

            {{-- لوحة المعلومات: مطابقة للوحة الفعلية --}}
            <div class="space-y-6 lg:pt-2" aria-hidden="true">
                {{-- بطاقة المقدمة --}}
                <div class="rounded-3xl border border-[#6B1F2B]/10 bg-[#F3E7DC] p-6 sm:p-8">
                    <div class="mb-4 h-7 w-40 animate-pulse rounded-full bg-stone-200"></div>
                    <div class="h-8 w-full animate-pulse rounded-xl bg-stone-200 sm:h-10"></div>
                    <div class="mt-3 h-4 w-11/12 animate-pulse rounded-full bg-stone-200"></div>
                    <div class="mt-2 h-4 w-4/5 animate-pulse rounded-full bg-stone-200"></div>
                </div>

                {{-- بطاقة التنبيه/المعلومة الخمرية --}}
                <div class="rounded-3xl bg-[#6B1F2B] p-6 sm:p-8">
                    <div class="mb-4 flex items-center gap-3">
                        <div class="h-11 w-11 shrink-0 animate-pulse rounded-2xl bg-white/15"></div>
                        <div class="h-4 w-32 animate-pulse rounded-full bg-white/20"></div>
                    </div>
                    <div class="h-4 w-full animate-pulse rounded-full bg-white/15"></div>
                    <div class="mt-2 h-4 w-4/5 animate-pulse rounded-full bg-white/15"></div>
                </div>

                {{-- بطاقتا التاريخ والضيوف --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-1">
                    @foreach ([
        'date' => 'reservation-form-skeleton-date',
        'guests' => 'reservation-form-skeleton-guests',
    ] as $type => $key)
                        <div wire:key="{{ $key }}"
                            class="flex items-start gap-4 rounded-2xl border border-stone-100 bg-white p-5 shadow-sm">
                            <div class="h-11 w-11 shrink-0 animate-pulse rounded-2xl bg-stone-100"></div>
                            <div class="min-w-0 flex-1 space-y-2">
                                <div class="h-4 w-2/5 animate-pulse rounded-lg bg-stone-200"></div>
                                <div class="h-3 w-4/5 animate-pulse rounded-full bg-stone-100"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>



</section>
