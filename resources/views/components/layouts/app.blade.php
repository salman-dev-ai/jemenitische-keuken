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


        <footer id="about" dir="rtl" class="relative isolate min-h-[620px] overflow-hidden bg-[#171421] text-stone-100">
            @include('components.layouts.partials.footer.footer-top', ['section' => 'backdrop'])

            <div class="mx-auto flex min-h-[620px] max-w-[1580px] flex-col px-8 pb-5 pt-8 sm:px-16 lg:px-[7.8%]">
                {{-- الترحيب واسم المكان والعنوان الرئيسي --}}
                @include('components.layouts.partials.footer.footer-top', ['section' => 'intro'])

                {{-- ثلاثة أعمدة: روابط، تواصل، ساعات --}}
                <div class="mt-6 grid flex-1 grid-cols-1 gap-3 lg:grid-cols-3 lg:gap-5">
                    @include('components.layouts.partials.footer.footer-top', ['section' => 'links'])
                    @include('components.layouts.partials.footer.footer-contact')
                    @include('components.layouts.partials.footer.footer-hours')
                </div>

                {{-- شريط الحقوق السفلي --}}
                @include('components.layouts.partials.footer.footer-bottom')
            </div>
        </footer>

        {{-- زر العودة للأعلى --}}
        @include('components.layouts.partials.scroll-to-top')

        {{-- إشعارات المتصفح الفورية --}}
        @include('components.layouts.partials.toasts')

        {{-- الأيقونات والنوافذ وسكربتات Livewire --}}
        @include('components.layouts.partials.scripts')
    </body>

</html>
