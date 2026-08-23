<x-layouts.app>

    <section id="home" class="py-20">
        <livewire:home-page />
    </section>

    <section id="reservation" class="py-20">
        <livewire:reservation-form />
    </section>

{{--
    <section id="menu" class="py-20">
        <livewire:menu-categories />
    </section> --}}


    <section id="gallery" class="py-20">
        <livewire:gallery-section />
    </section>

    <section id="contact_section" class="py-20">

        {{-- <livewire:contact-section /> --}}
        <livewire:contact-section />

    </section>

    <section id="customer_reviews" class="py-20">

        <livewire:customer-reviews-marquee />

    </section>



    <section id="menu_with_cart" class="py-20">

        <livewire:menu-with-cart />

    </section>

</x-layouts.app>
