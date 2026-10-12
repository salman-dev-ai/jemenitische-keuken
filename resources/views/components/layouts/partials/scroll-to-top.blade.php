{{--
    زر العودة إلى أعلى الصفحة.
    يراقب التمرير محلياً عبر Alpine ويظهر بعد ثلاثمئة بكسل
    ثم يعيد النافذة بسلاسة دون أي طلب للخادم.
--}}
{{-- الإظهار والإخفاء والانتقال كلها على العميل --}}
<button x-data="{ show: false }" @scroll.window="show = window.pageYOffset > 300"
    @click="window.scrollTo({ top: 0, behavior: 'smooth' })" x-show="show" x-cloak
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8"
    x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-8"
    class="fixed bottom-6 {{ app()->getLocale() == 'ar' ? 'left-6' : 'right-6' }} z-[60] flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-t from-[#E07513] to-[#B85709] text-white shadow-xl shadow-[#E07513]/30 transition-all hover:-translate-y-1 hover:shadow-2xl hover:shadow-[#E07513]/40 focus:outline-none focus:ring-2 focus:ring-[#FFD700]"
    aria-label="{{ __('messages.accessibility.scrollToTop') ?? 'العودة للأعلى' }}">
    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"
        stroke-linecap="round" stroke-linejoin="round">
        <path d="m18 15-6-6-6 6" />
    </svg>
</button>
