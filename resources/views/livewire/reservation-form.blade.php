{{--
الملف الرئيسي لنموذج الحجز. يستدعي رأس القسم ولافتة النجاح وبطاقة النموذج ولوحة المعلومات، ويجمع الصفحة دون نقل حالة الحقول خارج مكوّن Livewire.
--}}
<section class="relative overflow-hidden bg-[#FAF4ED] py-12 font-['Tajawal',sans-serif] sm:py-16 lg:py-20"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    {{-- يخفي العناصر المرتبطة بـ Alpine حتى يجهز التفاعل ولا تومض الخطوة الثانية. --}}
    @once
        <style>
            [x-cloak] { display: none !important; }
        </style>
    @endonce

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        @include('livewire.reservation.section-header')

        @include('livewire.reservation.success-banner')

        {{-- شبكة من عمودين تضع نموذج الحجز بجانب لوحة المعلومات على الشاشات الواسعة. --}}
        <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-2 lg:gap-10">
            @include('livewire.reservation.booking-form')

            @include('livewire.reservation.info-panel')
        </div>
    </div>
</section>
