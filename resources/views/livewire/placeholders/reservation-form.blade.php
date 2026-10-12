{{-- Skeleton placeholder for livewire/reservation-form --}}
<section class="relative overflow-hidden bg-[#FAF4ED] py-12 font-['Tajawal',sans-serif] sm:py-16 lg:py-20"
    x-data="{
        locale: document.documentElement.lang || 'ar',
        get isRtl() { return this.locale === 'ar'; }
    }"
    :dir="isRtl ? 'rtl' : 'ltr'"
    aria-busy="true"
    :aria-label="isRtl ? 'جاري التحميل...' : 'Loading...'">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- رأس القسم المطابق --}}
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
            {{-- بطاقة النموذج الرئيسية --}}
            <div class="relative overflow-hidden rounded-3xl border border-stone-100 bg-white p-6 shadow-sm sm:p-8"
                aria-hidden="true">
                <div class="absolute inset-x-0 top-0 h-1.5 rounded-t-3xl bg-stone-200"></div>

                {{-- شريط الخطوات --}}
                <div class="mb-7 flex items-center gap-3">
                    <template x-for="step in [1, 2, 3]" :key="step">
                        <div class="h-1.5 flex-1 animate-pulse rounded-full"
                            :class="step <= 2 ? 'bg-[#E07513]/30' : 'bg-stone-100'">
                        </div>
                    </template>
                </div>

                <div class="space-y-7">
                    {{-- الخطوة الأولى --}}
                    <div class="space-y-4">
                        <div class="flex items-center gap-2">
                            <div class="h-6 w-6 animate-pulse rounded-full bg-stone-200"></div>
                            <div class="h-4 w-32 animate-pulse rounded-full bg-stone-200"></div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="space-y-2 sm:col-span-2">
                                <div class="flex items-center justify-between">
                                    <div class="h-4 w-32 animate-pulse rounded-full bg-stone-200"></div>
                                    <div class="h-3 w-20 animate-pulse rounded-full bg-stone-100"></div>
                                </div>
                                <div class="h-12 w-full animate-pulse rounded-xl bg-stone-100"></div>
                                <div class="h-11 w-full animate-pulse rounded-xl bg-stone-100"></div>
                            </div>

                            <div class="space-y-2">
                                <div class="h-4 w-28 animate-pulse rounded-full bg-stone-200"></div>
                                <div class="h-12 w-full animate-pulse rounded-xl bg-stone-100"></div>
                                <div class="flex gap-2">
                                    <div class="h-7 flex-1 animate-pulse rounded-lg bg-stone-100"></div>
                                    <div class="h-7 flex-1 animate-pulse rounded-lg bg-stone-100"></div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="h-4 w-28 animate-pulse rounded-full bg-stone-200"></div>
                                <div class="h-12 w-full animate-pulse rounded-xl bg-stone-100"></div>
                                <div class="h-3 w-36 animate-pulse rounded-full bg-stone-100"></div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-stone-100"></div>

                    <div class="h-14 w-full animate-pulse rounded-2xl bg-[#6B1F2B]/20"></div>
                </div>
            </div>

            {{-- لوحة المعلومات الجانبية --}}
            <div class="space-y-6 lg:pt-2" aria-hidden="true">
                <div class="rounded-3xl border border-[#6B1F2B]/10 bg-[#F3E7DC] p-6 sm:p-8">
                    <div class="mb-4 h-7 w-40 animate-pulse rounded-full bg-stone-200"></div>
                    <div class="h-8 w-full animate-pulse rounded-xl bg-stone-200 sm:h-10"></div>
                    <div class="mt-3 h-4 w-11/12 animate-pulse rounded-full bg-stone-200"></div>
                    <div class="mt-2 h-4 w-4/5 animate-pulse rounded-full bg-stone-200"></div>
                </div>

                <div class="rounded-3xl bg-[#6B1F2B] p-6 sm:p-8">
                    <div class="mb-4 flex items-center gap-3">
                        <div class="h-11 w-11 shrink-0 animate-pulse rounded-2xl bg-white/15"></div>
                        <div class="h-4 w-32 animate-pulse rounded-full bg-white/20"></div>
                    </div>
                    <div class="h-4 w-full animate-pulse rounded-full bg-white/15"></div>
                    <div class="mt-2 h-4 w-4/5 animate-pulse rounded-full bg-white/15"></div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-1">
                    <template x-for="type in ['date', 'guests']" :key="type">
                        <div class="flex items-start gap-4 rounded-2xl border border-stone100 bg-white p-5 shadow-sm">
                            <div class="h-11 w-11 shrink-0 animate-pulse rounded-2xl bg-stone-100"></div>
                            <div class="min-w-0 flex-1 space-y-2">
                                <div class="h-4 w-2/5 animate-pulse rounded-lg bg-stone-200"></div>
                                <div class="h-3 w-4/5 animate-pulse rounded-full bg-stone-100"></div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</section>
