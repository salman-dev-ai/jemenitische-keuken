{{--
    بطاقة طلب واحدة: صورة بتدرج لوني، أيقونة دائرية، عنوان، وصف، وزر سهم يتغير اتجاهه حسب اللغة. تستقبل المتغيرات option وisArabic وindex، وتحوّل مفتاح الخيار إلى أيقونة Lucide مناسبة.
--}}

{{-- مفتاح الخيار القادم من البيانات، يُستخدم لاختيار أيقونة Lucide المناسبة بشكل ثابت --}}
@php
    $optionKey = $option['key'] ?? '';
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
            {{-- اختيار أيقونة Lucide ثابتة حسب مفتاح الخيار لتفادي المكوّن الديناميكي --}}
            @if ($optionKey === 'truck')
                <x-lucide-truck class="h-6 w-6 text-[#D9B36B]" />
            @elseif ($optionKey === 'shopping-bag')
                <x-lucide-shopping-bag class="h-6 w-6 text-[#D9B36B]" />
            @elseif ($optionKey === 'utensils')
                <x-lucide-utensils class="h-6 w-6 text-[#D9B36B]" />
            @elseif ($optionKey === 'clock')
                <x-lucide-clock class="h-6 w-6 text-[#D9B36B]" />
            @elseif ($optionKey === 'map-pin')
                <x-lucide-map-pin class="h-6 w-6 text-[#D9B36B]" />
            @elseif ($optionKey === 'credit-card')
                <x-lucide-credit-card class="h-6 w-6 text-[#D9B36B]" />
            @elseif ($optionKey === 'package')
                <x-lucide-package class="h-6 w-6 text-[#D9B36B]" />
            @elseif ($optionKey === 'store')
                <x-lucide-store class="h-6 w-6 text-[#D9B36B]" />
            @else
                <x-lucide-layout-grid class="h-6 w-6 text-[#D9B36B]" />
            @endif
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
            @if ($isArabic)
                <x-lucide-arrow-left class="h-4 w-4 transition-transform duration-300 group-hover:-translate-x-0.5" />
            @else
                <x-lucide-arrow-right class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5" />
            @endif
        </span>
    </div>
</a>
