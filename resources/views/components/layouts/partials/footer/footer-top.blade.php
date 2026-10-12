{{--
    الجزء العلوي من التذييل.
    يعرض خلفية المدينة، شريط الترحيب واسم المكان، ثم بطاقة الروابط السريعة.
    المعامل section يحدد المقطع: backdrop أو intro أو links.
--}}
@php
    $section = $section ?? 'intro';
    extract($footerLayout ?? [], EXTR_SKIP);
@endphp

@if ($section === 'backdrop')
    {{-- خلفية المدينة مع تدرج داكن فوقها --}}
    <div class="pointer-events-none absolute inset-0 -z-20 bg-[#171421]"></div>
    <div class="pointer-events-none absolute inset-0 -z-10 bg-cover bg-center bg-no-repeat opacity-100"
        style="background-image:url('{{ $backgroundImage }}')"></div>
    <div
        class="pointer-events-none absolute inset-0 -z-10 bg-[linear-gradient(180deg,rgba(15,18,37,.91)_0%,rgba(28,20,32,.80)_45%,rgba(31,12,5,.96)_100%)]">
    </div>
@endif

@if ($section === 'intro')
    {{-- شريط الترحيب واسم المكان --}}
    <div class="flex items-start justify-between text-[11px] font-medium text-white/85">
        <span>{{ $translate('messages.footer.welcome', 'مرحبا بكم  ً') }}</span>
        <div class="text-center leading-tight">
            <div class="text-[17px] font-black text-white">{{ $settings?->localized_name ?: 'المطبخ اليمني' }}</div>
            <div class="mt-1 text-[9px] font-bold tracking-[.18em] text-[#d69045]">JEMENITISCHE KEUKEN</div>
        </div>
    </div>

    {{-- العنوان الرئيسي وزر الحجز إن كان مفعلاً --}}
    <div class="mt-3 text-center">
        <p class="text-[10px] font-bold tracking-[.32em] text-[#d89043]">THE ORIGIN OF MANDI</p>
        <h1 class="mt-1 text-[30px] font-black leading-tight text-white drop-shadow-lg sm:text-[34px]">نكهات يمنية أصيلة
        </h1>
        <p class="mt-1 text-[12px] font-medium text-white/85">تجربة يمنية دافئة تجمع المذاق الأصيل والضيافة الكريمة.</p>
        @if ($settings?->accepts_reservations)
            <a href="#reservation"
                class="mt-3 inline-flex h-9 items-center justify-center rounded-full bg-[#df791d] px-7 text-[13px] font-black text-white shadow-[0_4px_18px_rgba(223,121,29,.42)] transition hover:-translate-y-0.5 hover:bg-[#ef8b2b]">
                {{ $translate('messages.footer.reserveNow', 'احجز طاولتك الآن') }}
            </a>
        @endif
    </div>
@endif

@if ($section === 'links')
    {{-- روابط سريعة داخل البطاقة الأولى --}}
    <section
        class="max-h-60 min-h-[200px] rounded-[16px] border border-white/30 bg-[linear-gradient(135deg,rgba(79,57,59,.70),rgba(47,31,34,.76))] p-5 shadow-[0_12px_30px_rgba(0,0,0,.27)] backdrop-blur-[5px]">
        <h2 class="flex items-center gap-2 text-[15px] font-black text-white">
            <span class="h-2 w-2 rounded-full bg-[#e17a18] shadow-[0_0_10px_rgba(225,122,24,.85)]"></span>
            {{ $translate('messages.footer.quickLinks', 'اكتشف') }}
        </h2>
        <ul class="mt-5 space-y-2.5 text-[13px] font-medium leading-5 text-white/80">
            <li>
                <a href="#home" class="transition-colors hover:text-[#f1ae62]">
                    {{ $translate('messages.nav.home', 'الرئيسية') }}
                </a>
            </li>
            <li>
                <a href="#menu" class="transition-colors hover:text-[#f1ae62]">
                    {{ $translate('messages.nav.menu', 'قائمة الطعام') }}
                </a>
            </li>
            <li>
                <a href="#gallery" class="transition-colors hover:text-[#f1ae62]">
                    {{ $translate('messages.nav.gallery', 'معرض الصور') }}
                </a>
            </li>
            @if ($settings?->accepts_reservations)
                <li>
                    <a href="#reservation" class="font-bold text-[#f0a54f] transition-colors hover:text-[#ffd28d]">
                        {{ $translate('messages.nav.reservation', 'الحجز') }}
                    </a>
                </li>
            @endif
        </ul>
    </section>
@endif
