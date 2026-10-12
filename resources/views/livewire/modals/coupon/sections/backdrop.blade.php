{{--
خلفية نافذة الكوبون. تعرّف حالة Alpine المتزامنة مع خاصية العرض
في Volt، وتدير الإظهار والإخفاء والانتقال وإغلاق النافذة فوراً.
--}}

{{-- يمنع إعادة تهيئة Alpine عند تحديث Livewire دون حجب الحقول. --}}
<div
    x-data="{ open: $wire.entangle('show').live }"
    x-show="open"
    x-cloak
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
    wire:ignore.self
    x-on:click.self="open = false"
    x-on:keydown.escape.window="if (open) open = false"
    role="dialog"
    aria-modal="true"
    aria-labelledby="coupon-modal-title"
    :aria-hidden="open ? 'false' : 'true'"
>
    {{-- يدرج بطاقة النافذة داخل الخلفية كي تشارك حالة الإظهار نفسها. --}}
    @include('livewire.modals.coupon.sections.container')
</div>
