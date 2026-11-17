{{--
أزرار التفاعل السريع والخدمات لسطح المكتب فقط.
كل زر: أيقونة معروفة + نص مكتوب تحتها (لمن لا يقرأ ولا يكتب).
--}}

@php
    $f = $footerLayout ?? [];
    extract($f, EXTR_SKIP);
    $whatsappHref = (string) ($whatsappHref ?? ($f['whatsapp_href'] ?? ''));

    $seoPage = (string) (request()->route()?->getName() ?? 'home');
    $seoPage = in_array($seoPage, ['home', 'restaurant-info', 'delivery-zones'], true) ? $seoPage : 'home';

    // كلاس مشترك: أيقونة فوق، نص تحت
    $btn = 'inline-flex h-14 min-w-[68px] shrink-0 flex-col items-center justify-center gap-0.5 rounded-xl border px-2 shadow-sm transition-all hover:-translate-y-0.5';
    $lbl = 'text-[11px] font-bold leading-none whitespace-nowrap';
@endphp

<div class="hidden shrink-0 items-center gap-2 lg:flex">

    {{-- الطلب / Bestellen --}}
    <a href="{{ route('home') }}#menu" @click="activeSection = 'menu'"
        class="{{ $btn }} border-gray-200 bg-white/70 text-gray-800 hover:border-[#E07513]/40 hover:text-[#E07513]">
        @svg('lucide-shopping-bag', 'h-6 w-6', ['aria-hidden' => 'true'])
        <span class="{{ $lbl }}">{{ __('messages.nav.order') }}</span>
    </a>

    {{-- العروض / Aanbiedingen --}}
    <a href="{{ route('home') }}#offers" @click="activeSection = 'offers'"
        class="{{ $btn }} border-gray-200 bg-white/70 text-gray-800 hover:border-[#E07513]/40 hover:text-[#E07513]">
        @svg('lucide-tag', 'h-6 w-6', ['aria-hidden' => 'true'])
        <span class="{{ $lbl }}">{{ __('messages.nav.offers') }}</span>
    </a>

    {{-- احجز طاولة / Reserveren — أيقونة الشوكة والسكين --}}
    <a href="{{ route('home') }}#reservation" @click="activeSection = 'reservation'"
        class="{{ $btn }} border-transparent bg-gradient-to-r from-[#E07513] to-[#B85709] text-white shadow-md shadow-[#E07513]/30 hover:shadow-lg hover:shadow-[#E07513]/40">
       @svg('icon-dining-table', 'h-7 w-7', ['aria-hidden' => 'true'])
        <span class="{{ $lbl }}">{{ __('messages.nav.reserve') }}</span>
    </a>

    {{-- التوصيل / Bezorgen --}}
    <a  href="{{ route('restaurant-info') }}#delivery"  rel="noopener noreferrer"
        class="{{ $btn }} border-gray-200 bg-white/70 text-gray-800 hover:border-[#E07513]/40">
        <img src="{{ asset('images/thuisbezorgd.png') }}" alt="" width="200" height="206"
            loading="lazy" decoding="async" class="h-6 w-6 object-contain" />
        <span class="{{ $lbl }}">{{ __('messages.nav.delivery') }}</span>
    </a>

    {{-- واتساب / WhatsApp --}}
    @if ($whatsappHref !== '')
        <a href="{{ $whatsappHref }}" target="_blank" rel="noopener noreferrer"
            class="{{ $btn }} border-[#25D366] bg-[#25D366] text-white shadow-md shadow-[#25D366]/30 hover:bg-[#1ebe5a]">
           @svg('icon-whatsapp', 'h-6 w-6', ['aria-hidden' => 'true'])
            <span class="{{ $lbl }}">{{ __('messages.nav.whatsapp') }}</span>
        </a>
    @endif

    {{-- محوّل اللغة / Language Switcher --}}
    <div class="relative shrink-0" x-data="{ open: false }">
        <button @click="open = !open" @keydown.escape.window="open = false" type="button"
            class="flex h-14 items-center gap-1.5 rounded-xl border border-gray-200 bg-white/70 px-3 text-xs font-bold text-gray-800 transition-all hover:bg-gray-100">
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