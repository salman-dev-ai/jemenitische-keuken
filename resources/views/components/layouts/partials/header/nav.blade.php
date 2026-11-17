{{--
شريط الروابط الرئيسي (روابط التصفح فقط — مفصول عن أزرار الخدمات).

- روابط أقسام الصفحة الرئيسية: يتحكم بها Alpine عبر المتغير activeSection.
- رابط "معلومات المطعم": صفحة مستقلة، فيُحدَّد نشاطه من الخادم (request()->routeIs)
  لأن إعادة تحميل الصفحة تُضيع حالة Alpine.

مهم: في الـ x-data الأب اضبط القيمة الابتدائية هكذا، حتى لا يبقى "الرئيسية" مفعّلاً
في صفحة معلومات المطعم:

    x-data="{ activeSection: @js(request()->routeIs('restaurant-info') ? 'restaurant-info' : 'home') }"

وإن كان لديك scroll-spy يغيّر activeSection، فشغّله على الصفحة الرئيسية فقط.
--}} 

<!-- 'about'   => 'messages.nav.about', -->
@php
    // مفتاح القسم => مفتاح الترجمة
    $sections = [
        'home'    => 'messages.nav.home',
       
        'gallery' => 'messages.nav.galleryHeritage',
        'contact' => 'messages.nav.contactLink',
    ];

    $isRestaurantInfo = request()->routeIs('restaurant-info');
@endphp

<nav class="w-full border-t border-gray-200/50 pt-2 lg:w-auto lg:border-t-0 lg:pt-0"
     aria-label="{{ __('messages.nav.primary') }}">
    <ul class="flex flex-wrap items-center justify-center gap-x-4 gap-y-2 text-xs font-bold text-gray-700 sm:gap-x-6 sm:text-sm lg:gap-x-8">

        {{-- أقسام الصفحة الرئيسية: الرئيسية / معلومات / معرض الصور وتراثنا / تواصل --}}
        @foreach ($sections as $id => $labelKey)
            <li>
                <a href="{{ route('home') }}#{{ $id }}"
                   @click="activeSection = '{{ $id }}'"
                   :aria-current="activeSection === '{{ $id }}' ? 'true' : null"
                   class="group relative block pb-1 transition-colors duration-300 hover:text-[#E07513]"
                   :class="activeSection === '{{ $id }}' ? 'text-[#E07513]' : ''">
                    {{ __($labelKey) }}
                    <span class="absolute bottom-0 start-0 h-0.5 bg-[#E07513] transition-all duration-300 ease-out"
                          :class="activeSection === '{{ $id }}' ? 'w-full' : 'w-0 group-hover:w-full'"></span>
                </a>
            </li>
        @endforeach

        {{-- معلومات المطعم (صفحة مستقلة): الحالة النشطة تأتي من الخادم --}}
        <li>
            <a href="{{ route('restaurant-info') }}"
               @if ($isRestaurantInfo) aria-current="page" @endif
               class="  group relative block pb-1 transition-colors duration-300 hover:text-[#E07513] {{ $isRestaurantInfo ? 'text-[#E07513]' : '' }}">
                {{ __('messages.nav.restaurantInfo') }}
                <span class="absolute bottom-0 start-0 h-0.5 bg-[#E07513] transition-all duration-300 ease-out {{ $isRestaurantInfo ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
            </a>
        </li>
    </ul>
</nav>