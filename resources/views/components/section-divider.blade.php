{{--
    resources/views/components/section-divider.blade.php

    حزام ذهبي عائم يتوسط الحد الفاصل بين قسمين دون أن يأخذ ارتفاعاً خاصاً به:
    خطوط ذهبية مزدوجة + معينات + شعار المطعم في المنتصف.

    الخيارات: variant = light (الافتراضي) أو dark، و logo = مسار الشعار داخل public.
    ملاحظة: لا تكتب وسم المكوّن نفسه ولا تعليقات متداخلة هنا، وإلا يستدعي المكوّن نفسه بلا نهاية.
--}}
@props([
    'variant' => 'light',
    'logo' => 'images/logo2.webP',
])

@php
    $isDark = $variant === 'dark';
    $hasLogo = $logo && file_exists(public_path($logo));
@endphp

{{-- غلاف بارتفاع صفر: لا يدفع الأقسام، والحزام يطفو فوق الحد بينهما --}}
<div {{ $attributes->class(['pointer-events-none relative z-20 h-0 w-full select-none']) }} dir="ltr" aria-hidden="true">
    <div class="absolute inset-x-0 top-0 -translate-y-1/2">
        <div class="mx-auto flex max-w-7xl items-center gap-3 px-4 sm:gap-5 sm:px-6 lg:px-8 ">

            {{-- الجانب الأيسر --}}
            <div class="flex flex-1 items-center gap-2 sm:gap-3">
                <span class="h-3 w-3 shrink-0 rotate-45 bg-gradient-to-br from-[#390001] to-[#BC5A0A] shadow-[0_0_8px_rgba(232,169,58,.55)] sm:h-3.5 sm:w-3.5"></span>
                <span class="flex flex-1 flex-col gap-[3px] drop-shadow-[0_1px_2px_rgba(74,14,14,.25)]">
                    <span class="h-[2px] w-full bg-gradient-to-r from-[#8A5A12] via-[#F3C969] to-[#C8892B]"></span>
                    <span class="h-px w-full bg-gradient-to-r from-transparent via-[#C8892B]/70 to-[#C8892B]"></span>
                </span>
                <span class="h-2 w-2 shrink-0 rotate-45 border border-[#E8A93A] bg-[#E8A93A]/30"></span>
            </div>

            {{-- المنتصف: الشعار --}}
            <div class="relative flex shrink-0 items-center justify-center">
                {{-- هالة ذهبية ناعمة --}}
                <span class="absolute h-20 w-20 rounded-full bg-[#E8A93A]/25 blur-xl sm:h-24 sm:w-24"></span>

                @if ($hasLogo)
                    @if ($isDark)
                        <img src="{{ asset($logo) }}" alt="" class="relative h-14 w-auto drop-shadow-[0_0_10px_rgba(232,169,58,.5)] sm:h-16">
                    @else
                        {{-- دائرة عنابية بإطار ذهبي كما في الهيدر --}}
                        <span class="relative grid h-16 w-16 place-items-center rounded-full bg-[#4A0E0E] ring-2 ring-[#E8A93A] ring-offset-2 ring-offset-[#FBF5EA] shadow-lg shadow-[#4A0E0E]/30 sm:h-[4.5rem] sm:w-[4.5rem]">
                            <img src="{{ asset($logo) }}" alt="" class="h-16 w-20 ">
                        </span>
                    @endif
                @else
                    {{-- بديل أنيق إن لم يوجد ملف الشعار --}}
                    <span class="relative flex items-center gap-2">
                        <span class="h-2 w-2 rotate-45 bg-[#E8A93A]"></span>
                        <span class="h-4 w-4 rotate-45 border-2 border-[#E8A93A] bg-[#4A0E0E]"></span>
                        <span class="h-2 w-2 rotate-45 bg-[#E8A93A]"></span>
                    </span>
                @endif
            </div>

            {{-- الجانب الأيمن (مرآة اليسار) --}}
            <div class="flex flex-1 -scale-x-100 items-center gap-2 sm:gap-3">
                <span class="h-3 w-3 shrink-0 rotate-45 bg-gradient-to-br from-[#390001] to-[#BC5A0A] shadow-[0_0_8px_rgba(232,169,58,.55)] sm:h-3.5 sm:w-3.5"></span>
                <span class="flex flex-1 flex-col gap-[3px] drop-shadow-[0_1px_2px_rgba(74,14,14,.25)]">
                    <span class="h-[2px] w-full bg-gradient-to-r from-[#8A5A12] via-[#F3C969] to-[#C8892B]"></span>
                    <span class="h-px w-full bg-gradient-to-r from-transparent via-[#C8892B]/70 to-[#C8892B]"></span>
                </span>
                <span class="h-2 w-2 shrink-0 rotate-45 border border-[#E8A93A] bg-[#E8A93A]/30"></span>
            </div>
        </div>
    </div>
</div>