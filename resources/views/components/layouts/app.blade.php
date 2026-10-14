{{--
الغلاف الرئيسي للصفحة.
يستدعي الرأس والمحتوى والتذييل والأدوات العائمة
دون أن يحمل منطق العرض داخله.
--}}
@props(['settings' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
    class="scroll-smooth">

{{-- يحسب بيانات التواصل والساعات مرة واحدة قبل التذييل --}}
@include('components.layouts.partials.helpers')


{{-- وسوم الرأس: الخطوط والأصول وأنماط Livewire --}}
@include('components.layouts.partials.head')

<body class="font-['Tajawal'] bg-[#FDFBF7] text-[#2C1810] antialiased selection:bg-[#E07513] selection:text-white">

    {{-- الهيدر الثابت وحالة Alpine الخاصة به --}}
    @include('components.layouts.partials.header.header-main')

    {{-- موضع بطل الصفحة، فارغ حالياً لأن المصدر بلا بطل --}}
    @include('components.layouts.partials.hero')

    {{-- محتوى الصفحة الممرر عبر الفتحة --}}
    <main>
        {{ $slot }}
    </main>

    <!-- #endregion -->
    <section id="about" class="">
        <livewire:footer />
    </section>

    {{-- زر العودة للأعلى --}}
    @include('components.layouts.partials.scroll-to-top')

    {{-- إشعارات المتصفح الفورية --}}
    @include('components.layouts.partials.toasts')

    {{-- الأيقونات والنوافذ وسكربتات Livewire --}}
    @include('components.layouts.partials.scripts')
</body>

</html>
