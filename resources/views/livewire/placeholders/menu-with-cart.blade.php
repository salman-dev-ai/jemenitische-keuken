{{-- Skeleton: livewire/menu-with-cart --}}
<section class="relative overflow-hidden bg-[#FAF6F0] py-10 border-b border-[#E8DFD3]"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" aria-busy="true"
    aria-label="{{ __('messages.accessibility.loading') ?? 'جاري التحميل...' }}  id="menu">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">

        {{-- الرأس --}}
        <div class="max-w-3xl mx-auto mb-12 space-y-4 flex flex-col items-center">
            <div class="h-7 w-36 rounded-full bg-stone-200 animate-pulse"></div>
            <div class="h-10 w-2/3 max-w-lg rounded-2xl bg-stone-200 animate-pulse"></div>
            <div class="h-4 w-1/2 max-w-sm rounded-full bg-stone-200 animate-pulse"></div>
        </div>

        {{-- بطاقات الأقسام --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-12">
            @foreach (range(1, 5) as $i)
                <div class="aspect-[4/3] rounded-2xl bg-stone-200 animate-pulse"></div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">

            {{-- القائمة الجانبية (شاشات كبيرة) --}}
            <aside class="hidden lg:block lg:col-span-3 mt-20">
                <div class="bg-white rounded-2xl border border-stone-200 p-2 space-y-1">
                    @foreach (range(1, 6) as $i)
                        <div class="h-12 rounded-xl bg-stone-100 animate-pulse"></div>
                    @endforeach
                </div>
            </aside>

            {{-- شريط الجوال --}}
            <div class="lg:hidden flex gap-2 overflow-hidden">
                @foreach ([64, 96, 88, 104, 80] as $w)
                    <div class="h-9 shrink-0 rounded-full bg-stone-200 animate-pulse" style="width: {{ $w }}px"></div>
                @endforeach
            </div>

            {{-- شبكة الأطباق --}}
            <div class="col-span-1 lg:col-span-9">
                <div class="hidden lg:flex items-center justify-between mb-6 pb-4 border-b border-stone-200">
                    <div class="h-6 w-40 rounded-lg bg-stone-200 animate-pulse"></div>
                    <div class="h-6 w-20 rounded-full bg-stone-100 animate-pulse"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach (range(1, 8) as $i)
                        <div class="overflow-hidden rounded-3xl bg-white border border-stone-100 shadow-sm">
                            <div class="relative aspect-[4/3] bg-stone-100 animate-pulse">
                                <div class="absolute top-3 start-3 h-6 w-20 rounded-lg bg-stone-200"></div>
                            </div>
                            <div class="p-5 space-y-3">
                                <div class="h-5 w-3/4 rounded-lg bg-stone-200 animate-pulse"></div>
                                <div class="space-y-1.5">
                                    <div class="h-3 w-full rounded-full bg-stone-100 animate-pulse"></div>
                                    <div class="h-3 w-4/5 rounded-full bg-stone-100 animate-pulse"></div>
                                </div>
                                <div class="pt-4 border-t border-stone-100">
                                    <div class="h-11 w-full rounded-xl bg-stone-200 animate-pulse"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
