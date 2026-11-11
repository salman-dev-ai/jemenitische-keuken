<x-layouts.app :settings="$settings">

    {{-- Home section --}}
    <div id="home" class="mt-2">
        <livewire:home-page />
    </div>

    <x-section-divider />

    {{-- Offers section --}}
    <section id="offers" class="py-10">
        <livewire:offers-section />
    </section>

    <x-section-divider />

    {{-- Gallery section --}}
    <section id="gallery" class="py-10">
        <livewire:gallery-section />
    </section>

    <x-section-divider />

    {{-- Menu with cart section --}}
    <section id="menu_with_cart">
        <livewire:menu-with-cart />
    </section>

    <x-section-divider />

    {{-- Popular dishes section --}}
    <section id="popular_dishes">
        <livewire:popular-dishes />
    </section>

    <x-section-divider />

    {{-- Reviews section --}}
    <section id="customer_reviews" class="py-20">
        <livewire:customer-reviews-marquee />
    </section>

    <x-section-divider />

    {{-- Reservation section --}}
    <section id="reservation" class="px-10">
        <livewire:reservation-form />
    </section>

    <x-section-divider />

    {{-- Contact section --}}
    <section id="contact"  >
        <livewire:contact-form />
    </section>
   <x-section-divider />
</x-layouts.app>