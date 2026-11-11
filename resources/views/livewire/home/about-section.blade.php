{{--
    قسم «من نحن»: صورة بحافة منحنية وإطار ذهبي، وشارة عنوان، ونص تعريفي، وزر، وقائمة مزايا جانبية، وموجة عنابية زخرفية في الأسفل.
    المتغيرات المطلوبة: $about (مصفوفة) و $isArabic و $isDutch.
    حقول $about: title, intro, body (فقرات تفصلها سطور فارغة), image, url, features (مصفوفة label و icon اختياري).
    الأيقونات عبر @svg من الخادم لتجنب مشكلة open_basedir، والاتجاه يعتمد على الخصائص المنطقية ليعمل RTL تلقائياً.
--}}

@php
    $about = $about ?? [];
    $badge = $isArabic ? 'من نحن' : ($isDutch ? 'Over ons' : 'About us');
    $cta = $isArabic ? 'اكتشف قصتنا' : ($isDutch ? 'Ontdek ons verhaal' : 'Discover our story');
    $paragraphs = filled($about['body'] ?? null) ? preg_split('/\R{2,}/', trim($about['body'])) : [];
    $features = collect($about['features'] ?? [])->take(4);
    $defaultIcons = ['lucide-concierge-bell', 'lucide-shield-check', 'lucide-heart', 'lucide-badge-check'];
@endphp

@if (filled($about['title'] ?? null))
    <section aria-labelledby="about-heading" id="about"
        class="relative isolate overflow-hidden bg-[#FBF3E8] pb-24 pt-10 text-[#4A0D12] sm:pt-14 lg:pb-32">

        <div class="pointer-events-none absolute inset-0 -z-10 bg-[radial-gradient(ellipse_at_bottom_right,#F3E2C4,transparent_60%)]"></div>

        <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 sm:px-8 lg:grid-cols-[1.05fr_1fr] lg:gap-12">

            {{-- الصورة: زاوية منحنية كبيرة وإطار ذهبي رفيع خلفها --}}
            <div class="relative">
                <div aria-hidden="true"
                    class="absolute -inset-1.5 rounded-[2.5rem] rounded-ee-[7rem] bg-gradient-to-br from-[#E8C88A] via-[#D9B36B] to-transparent"></div>
                <div class="relative aspect-[4/3] overflow-hidden rounded-[2.5rem] rounded-ee-[7rem] bg-[#4A0D12] shadow-2xl lg:aspect-[5/4]">
                    @if (! empty($about['image']))
                        <img src="{{ $about['image'] }}" alt="{{ $about['title'] }}" width="1920" height="816"
                            loading="lazy" decoding="async" class="h-full w-full object-cover">
                    @endif
                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-[#4A0D12]/30 via-transparent to-transparent"></div>
                </div>
            </div>

            {{-- المحتوى: الشارة والعنوان والنص والزر بجانب قائمة المزايا --}}
            <div class="grid gap-8 sm:grid-cols-[1fr_auto] sm:gap-6 lg:gap-8">
                <div class="text-start">
                    <span class="inline-flex items-center gap-2 rounded-full bg-gradient-to-b from-[#7A1620] to-[#4A0D12] px-6 py-2 font-serif text-lg font-bold text-[#FFF9EE] shadow-md">
                        @svg('lucide-crown', 'h-5 w-5 text-[#D9B36B]', ['aria-hidden' => 'true'])
                        {{ $badge }}
                    </span>

                    <h2 id="about-heading" class="mt-5 font-serif text-2xl font-bold leading-snug text-[#7A1620] sm:text-3xl lg:text-4xl">
                        {{ $about['title'] }}
                    </h2>

                    @if (filled($about['intro'] ?? null))
                        <p class="mt-4 text-sm font-semibold leading-7 sm:text-base">{{ $about['intro'] }}</p>
                    @endif

                    @foreach ($paragraphs as $paragraph)
                        <p class="mt-3 text-sm leading-7 text-[#4A0D12]/85">{{ $paragraph }}</p>
                    @endforeach

                    @if (! empty($about['url']))
                        <a href="{{ $about['url'] }}"
                            class="mt-7 inline-flex items-center gap-3 rounded-full bg-gradient-to-b from-[#7A1620] to-[#4A0D12] px-8 py-3.5 text-sm font-bold text-[#FFF9EE] shadow-lg ring-1 ring-[#D9B36B]/40 transition hover:-translate-y-0.5 hover:shadow-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#D9B36B]">
                            {{ $cta }}
                            @svg('lucide-arrow-right', 'h-4 w-4 rtl:rotate-180', ['aria-hidden' => 'true'])
                        </a>
                    @endif
                </div>

                {{-- قائمة المزايا مع خط ذهبي فاصل --}}
                @if ($features->isNotEmpty())
                    <ul class="space-y-5 border-t border-[#D9B36B]/70 pt-6 sm:border-s sm:border-t-0 sm:ps-6 sm:pt-0">
                        @foreach ($features as $i => $feature)
                            <li class="flex items-center gap-3">
                                <span class="flex size-12 shrink-0 items-center justify-center rounded-full bg-gradient-to-b from-[#F3D79A] to-[#D9B36B] text-[#4A0D12] shadow-md ring-2 ring-[#FFF9EE]">
                                    @svg($feature['icon'] ?? $defaultIcons[$i % count($defaultIcons)], 'h-6 w-6', ['aria-hidden' => 'true'])
                                </span>
                                <span class="max-w-[9rem] font-serif text-sm font-bold leading-tight">{{ $feature['label'] ?? '' }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- موجة عنابية بحافة ذهبية أسفل القسم --}}
        <svg aria-hidden="true" viewBox="0 0 1440 120" preserveAspectRatio="none"
            class="pointer-events-none absolute inset-x-0 bottom-0 -z-0 h-16 w-full sm:h-24 rtl:-scale-x-100">
            <path d="M0 70 C 320 10, 720 130, 1440 30 L1440 120 L0 120 Z" fill="#4A0D12" />
            <path d="M0 70 C 320 10, 720 130, 1440 30" fill="none" stroke="#D9B36B" stroke-width="3" />
        </svg>
    </section>
@endif