{{-- الأطباق المختارة إدارياً (is_featured) — العنوان محايد ولا يدّعي الأكثر مبيعاً --}}
<section dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
    class="bg-[#FAF6F0] py-14 text-[#2C1810]">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h2 class="mb-8 text-center text-3xl font-black">
            {{ __('messages.menu.popular_title') }}
        </h2>

        @if ($dishes->isEmpty())
            <p class="text-center text-stone-500">{{ __('messages.menu.empty') }}</p>
        @else
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-5">
                @foreach ($dishes as $dish)
                    <article wire:key="popular-{{ $dish->id }}"
                        class="group overflow-hidden rounded-3xl border border-stone-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                        <div class="relative aspect-[4/3] overflow-hidden bg-stone-100">
                            @if ($dish->image_path)
                                <img src="{{ Storage::url($dish->image_path) }}" alt="{{ $dish->localized_name }}"
                                    loading="lazy" width="600" height="450"
                                    class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-stone-50">
                                    @svg('lucide-soup', 'h-10 w-10 text-stone-300', ['aria-hidden' => 'true'])
                                </div>
                            @endif
                        </div>
                        <div class="space-y-1.5 p-4">
                            <h3 class="text-lg font-black leading-snug">{{ $dish->localized_name }}</h3>
                            @if ($dish->localized_description)
                                <p class="line-clamp-2 text-sm text-stone-500">{{ $dish->localized_description }}</p>
                            @endif
                            <p class="pt-1 text-sm font-black text-[#C8892B]">€{{ number_format($dish->price, 2) }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>