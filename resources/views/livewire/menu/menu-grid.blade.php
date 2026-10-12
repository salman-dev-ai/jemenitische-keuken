{{--
    شبكة الأطباق — تُرسَم كل الأطباق مرة واحدة والفلترة تتم في المتصفح (x-show)،
    لذلك تغيير القسم فوري تماماً.
    يتطلب computed property في المكوّن:  $this->menuItems  (كل الأطباق المتاحة مع category) — انظر الملاحظات.
--}}
@php
    $categoryNames = $this->categories->pluck('localized_name', 'slug');
    $itemCounts = $this->menuItems->groupBy(fn ($i) => $i->category->slug)->map->count();
@endphp

<div class="col-span-1 lg:col-span-9"
    x-data="{
        names: @js($categoryNames),
        counts: @js($itemCounts),
        total: {{ $this->menuItems->count() }},
        get visible() { return this.selected === 'all' ? this.total : (this.counts[this.selected] ?? 0); },
    }">

    {{-- عنوان القسم الحالي --}}
    <div class="hidden lg:flex items-center justify-between mb-6 pb-4 border-b border-stone-200">
        <h3 class="text-xl font-black text-[#2C0D0A] flex items-center gap-2">
            <x-lucide-crown x-show="selected === 'all'" class="w-5 h-5" aria-hidden="true" />
            <x-lucide-utensils x-show="selected !== 'all'" class="w-5 h-5" aria-hidden="true" />
            <span x-text="selected === 'all' ? @js(__('messages.menu.all')) : (names[selected] ?? @js(__('messages.menu.dishes')))"></span>
        </h3>
        <span class="text-xs font-bold text-stone-500 bg-stone-100 px-3 py-1 rounded-full">
            <span x-text="visible"></span>
            {{ trans_choice('messages.menu.dishes', 2) }}
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach ($this->menuItems as $item)
            @include('livewire.menu.menu-item-card', ['item' => $item])
        @endforeach

        {{-- حالة فارغة --}}
        <div x-show="visible === 0" x-cloak
            class="col-span-full text-center py-16 bg-stone-50 rounded-3xl border border-dashed border-stone-300">
            <x-lucide-utensils class="w-8 h-8 mx-auto mb-3 text-stone-400" aria-hidden="true" />
            <h3 class="text-lg font-bold text-stone-700">{{ __('messages.menu.empty') }}</h3>
        </div>
    </div>
</div>
