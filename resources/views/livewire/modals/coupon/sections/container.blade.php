{{--
حاوية النافذة الرئيسية. تطبق انتقال الظهور على البطاقة البيضاء
وتجمع الرأس ورمز الكوبون ونموذج تسجيل الضيف.
--}}

{{-- انتقال مستقل للبطاقة مع الإبقاء على حالة open الموروثة من الخلفية. --}}
<div
    x-show="open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 translate-y-2 scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
    x-transition:leave-end="opacity-0 translate-y-2 scale-95"
    class="w-full max-w-md max-h-[90vh] overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl"
    dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}"
>
    {{-- يستدعي رأس النافذة الذي يعرض العنوان والوصف المختصر أمام الضيف. --}}
    @include('livewire.modals.coupon.sections.header')

    {{-- يعرض رمز الكوبون الحالي قبل أن يبدأ الضيف بتعبئة بياناته. --}}
    @include('livewire.modals.coupon.sections.coupon-code')

    {{-- يضم نموذج تسجيل الضيف المرتبط مباشرة بدالة الإرسال في Volt. --}}
    @include('livewire.modals.coupon.sections.form')
</div>
