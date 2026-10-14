{{--
    الشريط السفلي. يعرض حقوق النشر مع السنة الحالية واسم المطعم وسطر العلامة.
--}}
{{-- شريط الحقوق السفلي --}}
<div
    class="mt-6 flex flex-col items-center justify-between gap-2 rounded-2xl bg-[#fbf3e8]/90 px-5 py-3 text-[11px] font-medium text-[#5b4030] shadow backdrop-blur sm:flex-row">
    <p>
        {{ __('footer.rights') }}
        <span dir="ltr">© {{ now()->year }}</span>
    </p>
    <p>{{ $f['name'] }} · {{ __('footer.brand_line') }}</p>
</div>
