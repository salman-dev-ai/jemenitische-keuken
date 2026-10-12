{{--
    بطاقة طلب واحدة: صورة بتدرج لوني، أيقونة دائرية، عنوان، وصف، وزر سهم يتغير اتجاهه حسب اللغة. تستقبل المتغيرات option وisArabic وindex، وتحوّل مفتاح الخيار إلى أيقونة Heroicons مناسبة.
--}}

{{-- تحويل مفتاح الخيار القادم من البيانات إلى اسم أيقونة Heroicons مناسبة --}}
@php
    $iconName = match ($option['key'] ?? '') {
        'truck' => 'heroicon-o-truck',
        'shopping-bag' => 'heroicon-o-shopping-bag',
        'utensils' => 'heroicon-o-cake',
        'clock' => 'heroicon-o-clock',
        'map-pin' => 'heroicon-o-map-pin',
        'credit-card' => 'heroicon-o-credit-card',
        'package' => 'heroicon-o-cube',
        'store' => 'heroicon-o-building-storefront',
        default => 'heroicon-o-squares-2x2',
    };
@endphp

<a href="#reservation" wire:key="order-card-{{ $index }}"
    class="group relative overflow-hidden rounded-xl border border-[#D9C9B4] bg-[#FFFDF8] shadow-[0_5px_16px_rgba(87,21,27,0.05)] transition-all duration-300 hover:-translate-y-1 hover:border-[#C89B4A] hover:shadow-[0_14px_28px_rgba(87,21,27,0.12)]">

    {{-- صورة البطاقة بتحميل متأخر وطبقة تدرج داكنة أسفلها لتحسين التباين --}}
    <div class="relative h-32 overflow-hidden sm:h-36">
        <img src="{{ $option['image'] }}" alt="{{ $option['title'] }}" width="800" height="420"
            loading="lazy" decoding="async"
            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
        <div class="absolute inset-0 bg-gradient-to-t from-[#57151B]/65 via-transparent to-transparent"></div>
    </div>

    <div class="relative px-5 pb-5 pt-0 text-center">

        {{-- الأيقونة الدائرية الذهبية البارزة فوق حافة الصورة وتكبر قليلاً عند المرور بالفأرة --}}
        <span
            class="relative -mt-6 mx-auto flex h-12 w-12 items-center justify-center rounded-full border-2 border-[#FBF7ED] bg-[#57151B] shadow-md transition-transform duration-300 group-hover:scale-105"
            aria-hidden="true">
            <x-dynamic-component :component="$iconName" class="h-6 w-6 text-[#D9B36B]" />
        </span>

        {{-- العنوان والوصف القادمان من عنصر البيانات الحالي في مصفوفة orderOptions --}}
        <h3 class="mt-3 font-serif text-2xl font-bold text-[#57151B]">
            {{ $option['title'] }}
        </h3>

        <p class="mt-1 text-xs text-[#B5863C]">
            {{ $option['description'] }}
        </p>

        {{-- زر السهم الدائري الذي ينعكس اتجاهه وحركته عند استخدام اللغة العربية --}}
        <span
            class="mx-auto mt-5 flex h-9 w-9 items-center justify-center rounded-full bg-[#C89B4A] text-[#FFF9EE] shadow-sm transition-all duration-300 group-hover:bg-[#B5863C] group-hover:shadow-md"
            aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                class="transition-transform duration-300 {{ $isArabic ? 'group-hover:-translate-x-0.5' : 'group-hover:translate-x-0.5' }}">
                @if ($isArabic)
                    <path d="m12 19-7-7 7-7" />
                    <path d="M19 12H5" />
                @else
                    <path d="M5 12h14" />
                    <path d="m12 5 7 7-7 7" />
                @endif
            </svg>
        </span>
    </div>
</a>
