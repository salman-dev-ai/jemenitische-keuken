{{-- حاوية في المنتصف تماماً --}}
<div
    class="fi-topbar-center-btn
            absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2  !mr-20
            pointer-events-none z-20">

    <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer"
        class="fi-btn fi-btn-size-sm pointer-events-auto inline-flex items-center gap-2 rounded-lg bg-amber-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-amber-700">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 3h6v6" />
            <path d="M10 14 21 3" />
            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
        </svg>
        <span class="hidden sm:inline">عرض موقع المستخدم</span>
    </a>
</div>
