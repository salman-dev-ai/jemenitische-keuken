<section id="gallery"
    class="relative overflow-hidden border-b border-[#E07513]/30 bg-[#1C0907] py-20 font-['Tajawal',sans-serif] text-white"
    dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}" aria-labelledby="gallery-heading">
    <div class="pointer-events-none absolute right-1/4 top-0 h-96 w-96 rounded-full bg-[#E07513]/10 blur-3xl"></div>
    <div class="pointer-events-none absolute bottom-0 left-1/4 h-96 w-96 rounded-full bg-[#B85709]/10 blur-3xl"></div>

    {{-- Decorative belt: IDs are unique because SVG fragment IDs are document-scoped. --}}
    <div class="mx-auto mb-8 max-w-7xl px-4" aria-hidden="true">
        <svg class="h-8 w-full text-amber-500 shadow-xl sm:h-10" viewBox="0 0 1200 40" preserveAspectRatio="none"
            fill="none">
            <defs>
                <linearGradient id="beltGoldGradTop" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#8A4500" />
                    <stop offset="25%" stop-color="#E07513" />
                    <stop offset="50%" stop-color="#FFD700" />
                    <stop offset="75%" stop-color="#E07513" />
                    <stop offset="100%" stop-color="#8A4500" />
                </linearGradient>
                <pattern id="janbiyaPatternTop" width="60" height="40" patternUnits="userSpaceOnUse">
                    <line x1="0" y1="4" x2="60" y2="4" stroke="url(#beltGoldGradTop)"
                        stroke-width="2" stroke-dasharray="3 2" />
                    <line x1="0" y1="36" x2="60" y2="36" stroke="url(#beltGoldGradTop)"
                        stroke-width="2" stroke-dasharray="3 2" />
                    <polygon points="30,8 52,20 30,32 8,20" fill="#2E0E0A" stroke="url(#beltGoldGradTop)"
                        stroke-width="2" />
                    <polygon points="30,12 44,20 30,28 16,20" fill="#E07513" opacity="0.6" stroke="#FFD700"
                        stroke-width="1" />
                    <circle cx="30" cy="20" r="3" fill="#FFD700" />
                    <line x1="0" y1="20" x2="8" y2="20" stroke="#FFD700"
                        stroke-width="1.5" />
                    <line x1="52" y1="20" x2="60" y2="20" stroke="#FFD700"
                        stroke-width="1.5" />
                </pattern>
            </defs>
            <rect width="1200" height="40" rx="6" fill="#210806" />
            <rect x="0" y="2" width="1200" height="36" fill="url(#janbiyaPatternTop)" />
            <rect width="1200" height="40" rx="6" stroke="url(#beltGoldGradTop)" stroke-width="2" />
        </svg>
    </div>

    <div class="relative z-10 mx-auto max-w-7xl px-4 text-right sm:px-6 lg:px-8">
        <div class="mx-auto mb-10 max-w-3xl space-y-3 text-center">
            <div
                class="inline-flex items-center gap-2 rounded-full border border-[#E07513]/40 bg-[#E07513]/20 px-4 py-1.5 text-xs font-bold text-amber-300">
                <span aria-hidden="true"><x-lucide-camera class="w-4 h-4" /></span>
                <span>{{ __('messages.gallery.badge') }}</span>
            </div>

            <h2 id="gallery-heading" class="text-2xl font-black tracking-tight text-white sm:text-4xl md:text-5xl">
                {{ __('messages.gallery.title') }}
            </h2>

            <p class="mx-auto max-w-2xl text-xs text-stone-300 sm:text-sm">
                {{ __('messages.gallery.subtitle') }}
            </p>
        </div>

        {{-- This was missing: categories are now visible and connected to selectCategory(). --}}
        <nav class="mb-8 flex flex-wrap justify-center gap-2" aria-label="{{ __('messages.gallery.categories') }}">
            @foreach ($this->categories as $category)
                <button type="button" wire:click="selectCategory('{{ $category['key'] }}')"
                    wire:loading.attr="disabled" @class([
                        'rounded-full border px-4 py-2 text-xs font-bold transition-colors sm:text-sm',
                        'border-[#FFD700] bg-[#E07513] text-white' =>
                            $selectedCategory === $category['key'],
                        'border-[#E07513]/40 bg-[#240B08]/80 text-amber-200 hover:border-[#FFD700] hover:bg-[#E07513]/20' =>
                            $selectedCategory !== $category['key'],
                    ])
                    aria-pressed="{{ $selectedCategory === $category['key'] ? 'true' : 'false' }}">
                    {{ $category['name'] }}
                </button>
            @endforeach
        </nav>

        <div wire:loading.flex wire:target="selectCategory"
            class="mb-4 items-center justify-center gap-2 text-sm text-amber-300" aria-live="polite">
            <span class="h-4 w-4 animate-spin rounded-full border-2 border-amber-300 border-t-transparent"
                aria-hidden="true"></span>
            <span>{{ __('messages.gallery.loading') }}</span>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3" aria-live="polite">
            @forelse($this->galleryItems as $item)
                <article wire:key="gallery-item-{{ $item->id }}"
                    class="group relative flex h-80 cursor-pointer flex-col justify-end overflow-hidden rounded-3xl border-2 border-[#E07513]/30 bg-[#250B08] shadow-xl transition-all duration-300 hover:-translate-y-1 hover:border-[#FFD700] hover:shadow-2xl">
                    @if ($item->image_url !== '')
                        <img src="{{ $item->image_url }}" alt="{{ $item->localized_alt }}" width="800"
                            height="640" loading="lazy" decoding="async"
                            class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.08]">
                    @else
                        <div class="absolute inset-0 bg-[#2E0E0A]" role="img"
                            aria-label="{{ $item->localized_alt }}"></div>
                    @endif

                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

                    <div
                        class="relative z-10 space-y-1.5  bg-gradient-to-t from-[#1C0907] to-transparent p-5">
                        @if ($item->localized_badge !== '')
                            <span
                                class="mb-1 inline-block rounded-full border border-[#E07513]/40 bg-[#240B08]/90 px-3 py-1 text-[11px] font-bold text-amber-300">
                                {{ $item->localized_badge }}
                            </span>
                        @endif
                        <h3 class="text-base font-extrabold text-white transition-colors group-hover:text-amber-300">
                            {{ $item->localized_name }}
                        </h3>
                        @if ($item->localized_description !== '')
                            <p class="line-clamp-2 text-xs text-stone-300">
                                {{ $item->localized_description }}
                            </p>
                        @endif
                    </div>
                </article>
            @empty
                <p class="col-span-full py-10 text-center text-stone-300">
                    {{ __('messages.gallery.empty') }}
                </p>
            @endforelse
        </div>
    </div>

    {{-- A second belt with its own IDs; it must not depend on the first SVG's <defs>. --}}
    <div class="mx-auto mt-12 max-w-7xl px-4" aria-hidden="true">
        <svg class="h-8 w-full text-amber-500 shadow-xl sm:h-10" viewBox="0 0 1200 40" preserveAspectRatio="none"
            fill="none">
            <defs>
                <linearGradient id="beltGoldGradBottom" x1="0%" y1="0%" x2="100%"
                    y2="0%">
                    <stop offset="0%" stop-color="#8A4500" />
                    <stop offset="25%" stop-color="#E07513" />
                    <stop offset="50%" stop-color="#FFD700" />
                    <stop offset="75%" stop-color="#E07513" />
                    <stop offset="100%" stop-color="#8A4500" />
                </linearGradient>
                <pattern id="janbiyaPatternBottom" width="60" height="40" patternUnits="userSpaceOnUse">
                    <line x1="0" y1="4" x2="60" y2="4"
                        stroke="url(#beltGoldGradBottom)" stroke-width="2" stroke-dasharray="3 2" />
                    <line x1="0" y1="36" x2="60" y2="36"
                        stroke="url(#beltGoldGradBottom)" stroke-width="2" stroke-dasharray="3 2" />
                    <polygon points="30,8 52,20 30,32 8,20" fill="#2E0E0A" stroke="url(#beltGoldGradBottom)"
                        stroke-width="2" />
                    <polygon points="30,12 44,20 30,28 16,20" fill="#E07513" opacity="0.6" stroke="#FFD700"
                        stroke-width="1" />
                    <circle cx="30" cy="20" r="3" fill="#FFD700" />
                    <line x1="0" y1="20" x2="8" y2="20" stroke="#FFD700"
                        stroke-width="1.5" />
                    <line x1="52" y1="20" x2="60" y2="20" stroke="#FFD700"
                        stroke-width="1.5" />
                </pattern>
            </defs>
            <rect width="1200" height="40" rx="6" fill="#210806" />
            <rect x="0" y="2" width="1200" height="36" fill="url(#janbiyaPatternBottom)" />
            <rect width="1200" height="40" rx="6" stroke="url(#beltGoldGradBottom)"
                stroke-width="2" />
        </svg>
    </div>
</section>
