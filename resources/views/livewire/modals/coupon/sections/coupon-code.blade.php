{{--
يعرض رمز الكوبون الحالي داخل إطار متقطع للتأكيد البصري
قبل أن يكمل الضيف بيانات التسجيل.
--}}

<div class="mb-5 flex items-center justify-between gap-2 rounded-xl border-2 border-dashed border-amber-300 bg-amber-50/60 px-4 py-3">
    {{-- الرمز نفسه باتجاه لاتيني حتى لا ينعكس في الواجهة العربية. --}}
    <code class="font-mono text-base font-bold tracking-wider text-amber-900" dir="ltr">
        {{ $couponCode }}
    </code>

    <svg class="h-5 w-5 shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
    </svg>
</div>
