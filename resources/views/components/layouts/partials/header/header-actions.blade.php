{{--
أزرار التفاعل السريع والخدمات لسطح المكتب فقط.
تتضمن: الطلب، الحجز (بارز)، العروض، الواتساب، ومحوّل اللغة.
مخفية تحت نقطة lg وتظهر بجانب روابط التصفح.
--}}

@php
    $f = $footerLayout ?? [];
    extract($f, EXTR_SKIP);
    $whatsappHref = (string) ($whatsappHref ?? ($f['whatsapp_href'] ?? ''));

    // 🔎 سيو: اسم صفحة الموقع الحالية لبناء روابط اللغات البديلة المباشرة.
    $seoPage = (string) (request()->route()?->getName() ?? 'home');
    $seoPage = in_array($seoPage, ['home', 'restaurant-info', 'delivery-zones'], true) ? $seoPage : 'home';
@endphp

<div class="hidden shrink-0 items-center gap-2.5 lg:flex">

    {{-- الطلب / Bestellen --}}
    <a href="{{ route('home') }}#menu" @click="activeSection = 'menu'"
        class="inline-flex h-10 items-center gap-1.5 rounded-xl border border-gray-200 bg-white/70 px-4 text-xs font-bold text-gray-800 shadow-sm transition-all hover:-translate-y-0.5 hover:border-[#E07513]/40 hover:text-[#E07513]">
        @svg('lucide-shopping-bag', 'h-4 w-4', ['aria-hidden' => 'true'])
        <span>{{ __('messages.nav.order') }}</span>
    </a>

    {{-- العروض / Aanbiedingen --}}
    <a href="{{ route('home') }}#offers" @click="activeSection = 'offers'"
        class="inline-flex h-10 items-center gap-1.5 rounded-xl border border-gray-200 bg-white/70 px-4 text-xs font-bold text-gray-800 shadow-sm transition-all hover:-translate-y-0.5 hover:border-[#E07513]/40 hover:text-[#E07513]">
        @svg('lucide-tag', 'h-4 w-4', ['aria-hidden' => 'true'])
        <span>{{ __('messages.nav.offers') }}</span>
    </a>

    {{-- الحجز / Reserveren — بارز بتدرج لوني --}}
    <a href="{{ route('home') }}#reservation" @click="activeSection = 'reservation'"
        class="inline-flex h-10 shrink-0 items-center justify-center gap-1.5 whitespace-nowrap rounded-xl bg-gradient-to-r from-[#E07513] to-[#B85709] px-5 text-sm font-bold text-white shadow-md shadow-[#E07513]/30 transition-all hover:-translate-y-0.5 hover:shadow-lg hover:shadow-[#E07513]/40">
        @svg('lucide-calendar-check', 'h-4 w-4', ['aria-hidden' => 'true'])
        <span>{{ __('messages.nav.reserve') }}</span>
    </a>

    {{-- واتساب / WhatsApp --}}
    @if ($whatsappHref !== '')
        <a href="{{ $whatsappHref }}" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"
            class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-emerald-200 bg-white text-emerald-600 shadow-sm transition-all hover:-translate-y-0.5 hover:border-emerald-500 hover:bg-emerald-50">
            @svg('lucide-message-circle', 'h-5 w-5', ['aria-hidden' => 'true'])
        </a>
    @endif

    {{-- محوّل اللغة / Language Switcher --}}
    <div class="relative shrink-0" x-data="{ open: false }">
        <button @click="open = !open" @keydown.escape.window="open = false" type="button"
            class="flex h-10 items-center gap-1.5 rounded-xl border border-gray-200 bg-white/70 px-3 text-xs font-bold text-gray-800 transition-all hover:bg-gray-100">
            <span>{{ app()->getLocale() == 'ar' ? 'العربية' : (app()->getLocale() == 'nl' ? 'NL' : 'EN') }}</span>
            @svg('lucide-chevron-down', 'h-3.5 w-3.5 text-gray-500 transition-transform duration-200', [':class' => "{ 'rotate-180': open }", 'aria-hidden' => 'true'])
        </button>
        <div x-show="open" @click.away="open = false" x-cloak
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-1"
            class="absolute right-0 z-50 mt-2 w-36 rounded-xl border border-gray-100 bg-white/95 py-1 text-xs shadow-2xl backdrop-blur-md">
            <a wire:navigate href="{{ route($seoPage, ['locale' => 'ar']) }}" hreflang="ar" lang="ar"
                class="block px-3 py-2 text-gray-700 hover:bg-[#E07513]/10 hover:text-[#E07513]">العربية</a>
            <a wire:navigate href="{{ route($seoPage, ['locale' => 'nl']) }}" hreflang="nl" lang="nl"
                class="block px-3 py-2 text-gray-700 hover:bg-[#E07513]/10 hover:text-[#E07513]">Nederlands</a>
            <a wire:navigate href="{{ route($seoPage, ['locale' => 'en']) }}" hreflang="en" lang="en"
                class="block px-3 py-2 text-gray-700 hover:bg-[#E07513]/10 hover:text-[#E07513]">English</a>
        </div>
    </div>
</div>