{{--
يجمع حقول تسجيل الضيف. الحقول المفردة ومجموعة العنوان تستدعي
حقل الإدخال المشترك حتى يبقى الربط مع Volt في مكان واحد.
--}}

{{-- حقل الاسم إلزامي ويرتبط مباشرة بحالة name داخل مكوّن Volt. --}}
@include('livewire.modals.coupon.sections.form-input', [
    'id' => 'coupon-name',
    'model' => 'name',
    'type' => 'text',
    'label' => __('offers.modal.name'),
    'required' => true,
    'autocomplete' => 'name',
])

{{-- حقل الهاتف إلزامي ويُكتب باتجاه لاتيني مع مثال للرقم الدولي. --}}
@include('livewire.modals.coupon.sections.form-input', [
    'id' => 'coupon-phone',
    'model' => 'phone',
    'type' => 'tel',
    'label' => __('offers.modal.phone'),
    'required' => true,
    'dir' => 'ltr',
    'autocomplete' => 'tel',
    'placeholder' => '+31 6 1234 5678',
])

{{-- حقل البريد اختياري ولا يمنع إرسال النموذج إذا تُرك فارغاً. --}}
@include('livewire.modals.coupon.sections.form-input', [
    'id' => 'coupon-email',
    'model' => 'email',
    'type' => 'email',
    'label' => __('offers.modal.email'),
    'required' => false,
    'dir' => 'ltr',
    'autocomplete' => 'email',
    'placeholder' => 'example@domain.com',
])

{{-- حقل العنوان اختياري ويأتي مباشرة قبل صف المدينة والرمز البريدي. --}}
@include('livewire.modals.coupon.sections.form-input', [
    'id' => 'coupon-address',
    'model' => 'address',
    'type' => 'text',
    'label' => __('offers.modal.address'),
    'required' => false,
    'autocomplete' => 'street-address',
    'placeholder' => __('offers.modal.address_placeholder'),
])

@include('livewire.modals.coupon.sections.form-address-group')
