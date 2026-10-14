{{--
    قسم المعرض والأطباق: صورة مميزة بعرض كامل مع شعار الحلال ووصف الطبق،
    ثم تبويبات الأقسام وشبكة البطاقات. العناصر المفعلة مرتبة من Livewire،
    والتصفية فورية عبر Alpine.js دون طلبات للخادم. متجاوب ويدعم RTL و LTR.
--}}
<section id="gallery" dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}" aria-labelledby="gallery-heading"
    class="relative overflow-hidden bg-[#FBF5EA] pb-14 font-['Tajawal',sans-serif] text-[#3A0D0D] sm:pb-20">

    <style>[x-cloak]{display:none!important}</style>

    {{-- الصورة المميزة بعرض كامل: صورة الطبق يتبعها تدرج كريمي خلف النص. --}}
    @php($featured = $this->featuredItem)
    <div class="relative">
        @if ($featured && $featured->image_url !== '')
            <img src="{{ $featured->image_url }}" alt="{{ $featured->localized_alt }}" width="1920" height="720"
                fetchpriority="high" decoding="async"
                class="h-72 w-full object-cover sm:h-96 lg:absolute lg:inset-0 lg:h-full">
        @else
            <div class="h-72 w-full bg-[#3A0D0D] sm:h-96 lg:absolute lg:inset-0 lg:h-full" aria-hidden="true"></div>
        @endif

        {{-- تدرج كريمي من جهة النص نحو الصورة، يظهر على الشاشات الكبيرة فقط. --}}
        <div aria-hidden="true"
            class="pointer-events-none absolute inset-0 hidden from-[#FBF5EA] from-[28%] via-[#FBF5EA]/85 via-[40%] to-transparent to-[62%] lg:block ltr:bg-linear-to-r rtl:bg-linear-to-l">
        </div>

        {{-- شارة الطبق المميز أعلى الصورة. --}}
        @if ($featured && $featured->localized_badge !== '')
            <div
                class="absolute end-4 top-4 z-10 flex items-center gap-2 rounded-xl border border-[#F2C879]/50 bg-[#1C0907]/85 px-4 py-2 text-sm font-bold text-[#F2C879] backdrop-blur-sm sm:end-8 sm:top-6">
                <x-lucide-star class="h-4 w-4 fill-[#F2C879]" aria-hidden="true" />
                <span>{{ $featured->localized_badge }}</span>
            </div>
        @endif

        {{-- شعار الحلال والعنوان ووصف الطبق المميز. --}}
        <div
            class="relative z-10 flex flex-col items-center gap-4 px-6 py-8 text-center sm:px-10 lg:min-h-[30rem] lg:w-5/12 lg:justify-center lg:py-12">
            <div
                class="flex h-24 w-24 flex-col items-center justify-center rounded-full border-4 border-[#C8892B] bg-[#4A0E0E] text-[#F2C879] shadow-lg ring-4 ring-[#C8892B]/30 sm:h-28 sm:w-28">
                <x-lucide-moon class="h-8 w-8 fill-[#F2C879]" aria-hidden="true" />
                <span class="text-xl font-black leading-none sm:text-2xl">{{ __('gallery.halal') }}</span>
            </div>

            <h2 id="gallery-heading" class="text-4xl font-black tracking-tight text-[#4A0E0E] sm:text-5xl">
                {{ __('gallery.halal_title') }}
            </h2>

            <div class="flex w-full items-center justify-center gap-3 text-base font-medium text-[#5C3A33] sm:text-lg">
                <span class="h-px w-10 bg-[#B8862B] sm:w-16" aria-hidden="true"></span>
                <p>{{ __('gallery.halal_subtitle') }}</p>
                <span class="h-px w-10 bg-[#B8862B] sm:w-16" aria-hidden="true"></span>
            </div>

            @if ($featured)
                <div class="flex items-center justify-center gap-3" aria-hidden="true">
                    <span class="h-px w-16 bg-[#B8862B]/70"></span>
                    <span class="h-2.5 w-2.5 rotate-45 bg-[#B8862B]"></span>
                    <span class="h-px w-16 bg-[#B8862B]/70"></span>
                </div>

                <div class="max-w-sm space-y-1">
                    <h3 class="text-xl font-extrabold text-[#4A0E0E]">{{ $featured->localized_name }}</h3>
                    @if ($featured->localized_description !== '')
                        <p class="text-sm leading-relaxed text-[#5C3A33] sm:text-base">
                            {{ $featured->localized_description }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- حالة القسم المختار محلية بالكامل داخل المتصفح. --}}
    <div class="relative z-10 mx-auto max-w-7xl px-4 pt-8 sm:px-6 lg:px-8" x-data="{ active: 'all' }">

        {{-- تبويبات الأقسام: تغيير فوري للقسم النشط. --}}
        @if ($this->categories->isNotEmpty())
            <nav class="mb-8 flex flex-wrap justify-center gap-2 sm:gap-3"
                aria-label="{{ __('gallery.categories') }}">
                <button type="button" @click="active = 'all'" :aria-pressed="active === 'all'"
                    :class="active === 'all'
                        ?
                        'border-[#4A0E0E] bg-[#4A0E0E] text-[#F2C879]' :
                        'border-[#4A0E0E]/70 bg-transparent text-[#4A0E0E] hover:bg-[#4A0E0E]/10'"
                    class="min-h-11 rounded-full border-2 px-6 text-sm font-bold transition-colors duration-200 sm:px-8 sm:text-base">
                    {{ __('gallery.all') }}
                </button>

                @foreach ($this->categories as $category)
                    <button type="button" wire:key="gallery-cat-{{ $category['key'] }}"
                        @click="active = @js($category['key'])" :aria-pressed="active === @js($category['key'])"
                        :class="active === @js($category['key'])
                            ?
                            'border-[#4A0E0E] bg-[#4A0E0E] text-[#F2C879]' :
                            'border-[#4A0E0E]/70 bg-transparent text-[#4A0E0E] hover:bg-[#4A0E0E]/10'"
                        class="min-h-11 rounded-full border-2 px-6 text-sm font-bold transition-colors duration-200 sm:px-8 sm:text-base">
                        {{ $category['name'] }}
                    </button>
                @endforeach
            </nav>
        @endif

        {{-- شبكة البطاقات: عمود واحد، ثم عمودان، ثم أربعة أعمدة. --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($this->galleryItems as $item)
                <article wire:key="gallery-item-{{ $item->id }}"
                    x-show="active === 'all' || active === @js($item->category)" x-transition.opacity.duration.200ms
                    x-cloak
                    class="group relative flex h-[22rem] flex-col justify-end overflow-hidden rounded-2xl border border-[#B8862B]/40 bg-[#2A0A08] shadow-lg shadow-[#4A0E0E]/20 transition-all duration-300 hover:-translate-y-1 hover:border-[#F2C879] hover:shadow-2xl">

                    {{-- صورة الطبق مع النص البديل للسيو. --}}
                    @if ($item->image_url !== '')
                        <img src="{{ $item->image_url }}" alt="{{ $item->localized_alt }}" width="800"
                            height="900" loading="lazy" decoding="async"
                            class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @else
                        <div class="absolute inset-0 bg-[#3A0D0D]" role="img"
                            aria-label="{{ $item->localized_alt }}"></div>
                    @endif

                    <div class="absolute inset-0 bg-linear-to-t from-[#140403]/95 via-[#140403]/45 to-transparent">
                    </div>

                    {{-- الشارة المميزة أعلى البطاقة. --}}
                    @if ($item->localized_badge !== '')
                        <span
                            class="absolute end-3 top-3 z-10 rounded-lg border border-[#F2C879]/40 bg-[#1C0907]/85 px-3 py-1 text-xs font-bold text-[#F2C879] backdrop-blur-sm">
                            {{ $item->localized_badge }}
                        </span>
                    @endif

                    {{-- العنوان والوصف أسفل البطاقة. --}}
                    <div class="relative z-10 space-y-1.5 p-5 text-center">
                        <h3 class="text-xl font-extrabold text-white transition-colors group-hover:text-[#F2C879]">
                            {{ $item->localized_name }}
                        </h3>
                        @if ($item->localized_description !== '')
                            <p class="line-clamp-2 text-sm leading-relaxed text-stone-200">
                                {{ $item->localized_description }}
                            </p>
                        @endif
                    </div>
                </article>
            @empty
                <p class="col-span-full py-10 text-center text-[#5C3A33]">
                    {{ __('gallery.empty') }}
                </p>
            @endforelse
        </div>
    </div>
</section>
