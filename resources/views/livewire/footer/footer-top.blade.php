{{--
    الجزء العلوي: الخلفية التراثية، الشعار ومبدل اللغة، العنوان الرئيسي،
    وبطاقة الطبق المميز. يستقبل بيانات التذييل من مكوّن Livewire.
--}}
{{-- الخلفية والتدرج الدافئ فوق الصورة --}}
<div class="pointer-events-none absolute inset-0 -z-10">
    <img src="{{ asset('images/footer-background.webp') }}" alt="" loading="lazy" decoding="async"
        class="h-full w-full object-cover object-bottom">
    <div class="absolute inset-0 bg-gradient-to-b from-[#f3c9a0]/70 via-[#f1c9a6]/55 to-[#d9a273]/70"></div>
</div>

{{-- الشعار واسم المطعم وشارة الموقع ومبدّل اللغة --}}
<div class="flex flex-wrap items-start justify-between gap-4">
    <div class="flex items-center gap-3">
        <img src="{{ asset('images/logo.jpg') }}" alt="logo" width="56" height="56" loading="lazy"
            class="h-12 w-12 rounded-full border-2 border-[#8a3b12] bg-[#2b1810] object-cover shadow-lg sm:h-14 sm:w-14">
        <div class="leading-tight">
            <p class="text-lg font-black text-[#2b1810] sm:text-xl">{{ $f['name'] }}</p>
            <p class="mt-1 text-[10px] font-bold tracking-[.18em] text-[#c05a1c]">{{ $f['name_sub'] }}</p>
        </div>
    </div>

    <div class="flex items-center gap-2">
        <span
            class="hidden rounded-full bg-white/70 px-4 py-2 text-xs font-medium text-[#5b4030] shadow-sm backdrop-blur sm:inline-block">
            {{ __('footer.badge') }}
        </span>
        {{-- أزرار تبديل اللغة عبر Livewire --}}
        {{-- <div class="flex items-center gap-1 rounded-full bg-white/70 p-1 shadow-sm backdrop-blur"
            role="group" aria-label="{{ __('footer.language') }}">
            @foreach ($f['locales'] as $code)
                <button type="button" wire:click="setLocale('{{ $code }}')"
                    @class([
                        'rounded-full px-3 py-1 text-[11px] font-bold uppercase transition',
                        'bg-[#c05a1c] text-white' => $code === $locale,
                        'text-[#5b4030] hover:bg-white' => $code !== $locale,
                    ])>
                    {{ $code }}
                </button>
            @endforeach
        </div> --}}


    </div>
</div>

{{-- العنوان الرئيسي والوصف المختصر --}}
<div class="mt-8 text-start sm:mt-10">
    <p class="text-xs font-bold text-[#c05a1c] sm:text-sm">{{ __('footer.eyebrow') }}</p>
    <h2 class="mt-2 text-3xl font-black leading-tight text-[#2b1810] sm:text-4xl lg:text-5xl">
        {{ __('footer.heading') }}
    </h2>
    <p class="mt-2 text-sm text-[#5b4030] sm:text-base">{{ __('footer.subheading') }}</p>
</div>

{{-- بطاقة الطبق المميز مع الصورة --}}
<div class="relative mt-6 overflow-hidden rounded-3xl shadow-[0_20px_50px_rgba(60,30,10,.28)]">
    <img src="{{ asset('images/mandi-story-photo.webp') }}" alt="{{ $f['featured_title'] }}" loading="lazy" decoding="async"
        class="h-64 w-full object-cover sm:h-80 lg:h-[22rem]">
    <div
        class="p-3 sm:absolute sm:inset-y-0 sm:end-0 sm:flex sm:w-[46%] sm:items-center sm:p-5 lg:w-[38%] lg:p-6">
        <div     class="w-full rounded-2xl bg-[#fbf3e8]/95 p-5 text-start shadow-xl backdrop-blur sm:p-6">
            <span
                class="inline-block rounded-full bg-[#c05a1c] px-3 py-1 text-[11px] font-bold text-white">
                {{ __('footer.featured_label') }}
            </span>
            <h3 class="mt-3 text-2xl font-black leading-snug text-[#2b1810] lg:text-3xl">
                {{ $f['featured_title'] }}
            </h3>
            <p class="mt-3 text-sm leading-7 text-[#6a5444]">{{ $f['featured_text'] }}</p>
            <a href="#menu"
                class="mt-4 inline-flex h-11 items-center gap-2 rounded-xl bg-[#c05a1c] px-6 text-sm font-black text-white shadow-md transition hover:-translate-y-0.5 hover:bg-[#a84a13]">
                {{ __('footer.explore_menu') }}
                <span aria-hidden="true" class="rtl:rotate-180">→</span>
            </a>
        </div>
    </div>
</div>
