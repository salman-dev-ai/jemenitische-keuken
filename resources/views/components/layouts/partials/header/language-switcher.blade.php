{{--
    محوّل اللغة / Language Switcher
    جزء مشترك يُستدعى في الهيدر (سطح المكتب) وفي أدوات الجوال.
    حالة Alpine محلية (open) مستقلة عن قوائم الهيدر.

    🔎 سيو: كل رابط يقود مباشرةً إلى نسخة الصفحة الحالية باللغة المطلوبة
    (/nl /en /ar) بدل تمرير اللغة عبر الجلسة، لتفهرس محركات البحث رابطاً لكل لغة.
--}}

@php
    // اسم صفحة الموقع الحالية لبناء روابط اللغات البديلة (مع تجاهل أي مسار غير صفحة موقع).
    $seoPage = (string) (request()->route()?->getName() ?? 'home');
    $seoPage = in_array($seoPage, ['home', 'restaurant-info', 'delivery-zones'], true) ? $seoPage : 'home';
@endphp

<div class="relative shrink-0" x-data="{ open: false }">
    <button @click="open = !open" @keydown.escape.window="open = false" type="button"
        class="flex h-10 items-center gap-1.5 rounded-xl border border-gray-200 bg-white/70 px-3 text-xs font-bold text-gray-800 transition-all hover:bg-gray-100">
        <span class='text-[16px] font-bold'>{{ app()->getLocale() == 'ar' ? 'ع' : (app()->getLocale() == 'nl' ? 'NL' : 'EN') }}</span>
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
