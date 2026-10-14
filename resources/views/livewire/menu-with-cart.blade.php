{{--
    menu-with-cart (الملف الرئيسي)

    الفكرة:
    - Alpine يملك حالة الواجهة الفورية: القسم المختار + فتح/إغلاق سلة الطلب (بدون أي رحلة خادم).
    - $wire.$entangle يُبقي القيمتين متزامنتين مع Livewire، فتصل للخادم مع أول طلب حقيقي (إضافة للسلة، checkout...).
    - كل الملفات الفرعية عبر @include، فهي جزء من نفس مكوّن Livewire:
      wire:click / wire:model تعمل مباشرة، وتورّث متغيرات Alpine (selected, isCartOpen).

    مطلوب في الـ CSS العام:  [x-cloak] { display: none !important; }
--}}
<section id="menu" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
    x-data="{
        selected: $wire.$entangle('selectedCategorySlug'),
        isCartOpen: $wire.$entangle('isCartModalOpen'),
    }"
    x-effect="document.body.classList.toggle('overflow-hidden', isCartOpen)"
    class="py-10 bg-[#FAF6F0] text-[#2C1810] relative overflow-hidden border-b border-[#E8DFD3]">

    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        @include('livewire.menu.header')
        @include('livewire.menu.category-cards')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
            @include('livewire.menu.sidebar-nav')
            @include('livewire.menu.mobile-nav')
            @include('livewire.menu.menu-grid')
        </div>
    </div>

    @include('livewire.menu.cart-button')
    @include('livewire.menu.cart.modal')

    {{-- نافذة كوبوناتي: تبقى مربوطة بالخادم لأنها تجلب بيانات (انظر الملاحظات) --}}
    {{-- @if ($isMyCouponsModalOpen) --}}
        @include('livewire.menu.cart.my-coupons-modal')
    {{-- @endif --}}
</section>
