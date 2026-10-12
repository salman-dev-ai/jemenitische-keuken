{{--
رأس النافذة. يعرض أيقونة الكوبون وعنوان التسجيل والوصف المختصر
المأخوذ من ملفات الترجمة دون أي حالة أو حدث.
--}}

<div class="mb-4 flex items-center gap-3">
    {{-- أيقونة الكوبون التوضيحية والثابتة تظهر هنا بجانب عنوان النافذة الرئيسي. --}}
    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-amber-100">
        <svg class="h-6 w-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
        </svg>
    </div>

    {{-- عنوان النافذة ووصفها القصير المرتبطان بتسمية الحوار من أجل الوصول. --}}
    <div>
        <h3 id="coupon-modal-title" class="text-xl font-bold text-amber-900">
            {{ __('offers.modal.title') }}
        </h3>
        <p class="mt-0.5 text-sm text-gray-600">
            {{ __('offers.modal.subtitle') }}
        </p>
    </div>
</div>
