<x-layouts.app :settings="$settings">

    {{-- Home section --}}
    <div id="home" class="py-0">
        <livewire:home-page />
    </div>

    {{-- Gallery section --}}
    <section id="gallery" class="py-10">
        <livewire:gallery-section />
    </section>

    {{-- Menu with cart section --}}
    <section id="menu_with_cart" class="py-20">
        <livewire:menu-with-cart />
    </section>

    {{-- Reviews section --}}
    <section id="customer_reviews" class="py-20">
        <livewire:customer-reviews-marquee />
    </section>

    {{-- Reservation section --}}
    <section id="reservation" class="py-20">
        <livewire:reservation-form />
    </section>

</x-layouts.app>
