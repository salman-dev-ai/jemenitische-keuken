{{--
    السطح السفلي للتذييل.
    يعرض حقوق النشر مع السنة الحالية
    وسطر العلامة The Origin Of Mandi.
--}}
@php
    extract($footerLayout ?? [], EXTR_SKIP);
@endphp

{{-- فاصل علوي ثم الحقوق والشعار النصي --}}
<div class="mt-5 flex items-center justify-between border-t border-[#c57832]/70 pt-3 text-[10px] font-medium text-white/65">
    <p>
        {{ $translate('messages.footer.rights', 'جميع الحقوق محفوظة') }}
        <span dir="ltr">© {{ now()->year }}</span>
    </p>
    <p class="text-[#d69348]">The Origin Of Mandi • أصل المندي</p>
</div>
