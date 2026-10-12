{{--
    طبقة صور الهيرو: تعرض صورة كل شريحة بانتقال تلاشي وتكبير هادئ، مع طبقتي تدرج لوني تحافظان على وضوح النص. تعتمد على المتغيرات slides وactiveSlide المعرفة في Alpine.js داخل ملف السلايدر الأب.
--}}

{{-- حلقة Alpine تكرر عنصر الصورة لكل شريحة وتظهر النشطة فقط --}}
<template x-for="(slide, index) in slides" :key="index">
    <div x-show="activeSlide === index"
        x-transition:enter="transition ease-out duration-700"
        x-transition:enter-start="opacity-0 scale-[1.03]"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-500 absolute inset-0"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0">

        {{-- تحميل مبكر لجميع الصور لتجنب وميض الشاشة عند الانتقال، والأولوية العالية للأولى --}}
        <img :src="slide.image" :alt="slide.title" width="1600" height="1000"
            loading="eager" :fetchpriority="index === 0 ? 'high' : 'low'"
            decoding="async"
            class="absolute inset-0 h-full w-full object-cover object-center transition-transform duration-[7000ms] ease-out will-change-transform"
            :class="activeSlide === index ? 'scale-105' : 'scale-100'">

        {{-- طبقتا تدرج لوني أفقي وعمودي لضمان وضوح النص فوق صورة الشريحة --}}
        <div
            class="pointer-events-none absolute inset-0 bg-gradient-to-l from-[#4A0D12]/95 via-[#4A0D12]/60 to-[#4A0D12]/5">
        </div>
        <div
            class="pointer-events-none absolute inset-0 bg-gradient-to-t from-[#4A0D12]/75 via-transparent to-transparent">
        </div>
    </div>
</template>
