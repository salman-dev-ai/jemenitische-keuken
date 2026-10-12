{{--
مجموعة العنوان الفرعية. تعرض المدينة والرمز البريدي جنباً إلى
جنب عبر حقل الإدخال المشترك، وكلاهما اختياري.
--}}

{{-- صف من عمودين يبقى متجاوراً داخل عرض النافذة الضيق الحالي. --}}
<div class="grid grid-cols-2 gap-3">
    @include('livewire.modals.coupon.sections.form-input', [
        'id' => 'coupon-city',
        'model' => 'city',
        'type' => 'text',
        'label' => __('offers.modal.city'),
        'required' => false,
        'autocomplete' => 'address-level2',
        'placeholder' => __('offers.modal.city_placeholder'),
    ])

    @include('livewire.modals.coupon.sections.form-input', [
        'id' => 'coupon-postal-code',
        'model' => 'postal_code',
        'type' => 'text',
        'label' => __('offers.modal.postal_code'),
        'required' => false,
        'dir' => 'ltr',
        'autocomplete' => 'postal-code',
        'placeholder' => '1012 NK',
    ])
</div>
