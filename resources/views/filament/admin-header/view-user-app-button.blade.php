{{-- حاوية في المنتصف تماماً --}}
<div
    class="fi-topbar-center-btn
            absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2  !mr-20
            pointer-events-none z-20">

    <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer"
        class="fi-btn fi-btn-size-sm pointer-events-auto inline-flex items-center gap-2 rounded-lg bg-amber-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-amber-700">
        @svg('lucide-external-link', 'h-4 w-4', ['aria-hidden' => 'true'])
        <span class="hidden sm:inline">عرض موقع المستخدم</span>
    </a>
</div>
