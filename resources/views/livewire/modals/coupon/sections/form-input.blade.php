{{--
حقل إدخال قابل لإعادة الاستخدام. يتوقع id و model و type و label
و required، ومعها placeholder و dir و autocomplete عند الحاجة.
يربط الحقل بخاصية Volt ويعرض رسالة التحقق الخاصة به.
--}}

{{-- غلاف الحقل: التسمية ثم الإدخال ثم خطأ التحقق إن وجد. --}}
<div>
    <label for="{{ $id }}" class="mb-1 block text-sm font-medium text-gray-700">
        {{ $label }}

        @if ($required)
            <span class="text-rose-500" aria-hidden="true">*</span>
        @else
            <span class="text-gray-400 text-xs font-normal">({{ __('offers.modal.optional') }})</span>
        @endif
    </label>

    <input
        id="{{ $id }}"
        type="{{ $type ?? 'text' }}"
        wire:model="{{ $model }}"
        @isset($dir) dir="{{ $dir }}" @endisset
        @isset($autocomplete) autocomplete="{{ $autocomplete }}" @endisset
        @isset($placeholder) placeholder="{{ $placeholder }}" @endisset
        class="w-full rounded-lg border px-4 py-2.5 focus:outline-none transition
               @error($model) border-rose-300 focus:border-rose-500 focus:ring-2 focus:ring-rose-200
               @else border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 @enderror"
    >

    @error($model)
        <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span>
    @enderror
</div>
