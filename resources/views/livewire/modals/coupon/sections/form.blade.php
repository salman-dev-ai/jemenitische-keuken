{{--
النموذج الرئيسي لتسجيل الضيف. يرسل البيانات إلى دالة submitGuest
في مكوّن Volt، ويضم الحقول مع أزرار الإلغاء والإرسال.
--}}

<form wire:submit="submitGuest" class="space-y-4">
    {{-- يدرج هنا كل حقول التسجيل الإلزامية والاختيارية الخاصة بالعميل الجديد. --}}
    @include('livewire.modals.coupon.sections.form-fields')

    {{-- يضع زري الإلغاء والإرسال معاً في أسفل حقول نموذج التسجيل. --}}
    @include('livewire.modals.coupon.sections.form-buttons')
</form>
