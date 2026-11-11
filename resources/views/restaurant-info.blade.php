{{--
    غلاف صفحة "معلومات المطعم" المستقلة.
    يعيد استخدام الغلاف الرئيسي (x-layouts.app) ثم يضع مكوّن Livewire للمعلومات داخله.
--}}

<x-layouts.app :settings="$settings">
    <section id="restaurant_info" class="px-2 py-8 sm:px-4 sm:py-12">
        <livewire:restaurant-info />
    </section>
</x-layouts.app>
