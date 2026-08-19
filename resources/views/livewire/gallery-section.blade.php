{{-- resources/views/livewire/gallery-section.blade.php --}}
<section id="gallery" class="py-20 bg-[#1C0907] text-white relative overflow-hidden border-b border-[#E07513]/30 font-['Tajawal',sans-serif]" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- توهج جمالي --}}
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-[#E07513]/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-[#B85709]/10 rounded-full blur-3xl pointer-events-none"></div>

    {{-- 1. حزام الجنبية اليمانية العلوي المطرز بخيوط الذهب --}}
    <div class="max-w-7xl mx-auto px-4 mb-8">
        <svg class="w-full h-8 sm:h-10 text-amber-500 shadow-xl" viewBox="0 0 1200 40" preserveAspectRatio="none" fill="none">
            <defs>
                <linearGradient id="beltGoldGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#8A4500" />
                    <stop offset="25%" stop-color="#E07513" />
                    <stop offset="50%" stop-color="#FFD700" />
                    <stop offset="75%" stop-color="#E07513" />
                    <stop offset="100%" stop-color="#8A4500" />
                </linearGradient>
                <pattern id="janbiyaPattern" width="60" height="40" patternUnits="userSpaceOnUse">
                    <line x1="0" y1="4" x2="60" y2="4" stroke="url(#beltGoldGrad)" stroke-width="2" stroke-dasharray="3 2" />
                    <line x1="0" y1="36" x2="60" y2="36" stroke="url(#beltGoldGrad)" stroke-width="2" stroke-dasharray="3 2" />
                    {{-- المعين التراثي لحزام الجنبية اليمانية --}}
                    <polygon points="30,8 52,20 30,32 8,20" fill="#2E0E0A" stroke="url(#beltGoldGrad)" stroke-width="2" />
                    <polygon points="30,12 44,20 30,28 16,20" fill="#E07513" opacity="0.6" stroke="#FFD700" stroke-width="1" />
                    <circle cx="30" cy="20" r="3" fill="#FFD700" />
                    <line x1="0" y1="20" x2="8" y2="20" stroke="#FFD700" stroke-width="1.5" />
                    <line x1="52" y1="20" x2="60" y2="20" stroke="#FFD700" stroke-width="1.5" />
                </pattern>
            </defs>
            <rect x="0" y="0" width="1200" height="40" fill="#210806" rx="6" />
            <rect x="0" y="2" width="1200" height="36" fill="url(#janbiyaPattern)" />
            <rect x="0" y="0" width="1200" height="40" stroke="url(#beltGoldGrad)" stroke-width="2" rx="6" />
        </svg>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-right">

        {{-- رأس القسم --}}
        <div class="text-center max-w-3xl mx-auto space-y-3 mb-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#E07513]/20 border border-[#E07513]/40 text-amber-300 text-xs font-bold">
                <span>📸</span>
                <span>{{ __('messages.gallery.badge') ?? 'معرض الصور التراثي المطرز' }}</span>
            </div>

            <h2 class="text-2xl sm:text-4xl md:text-5xl font-black text-white tracking-tight">
                <span>{{ __('messages.gallery.title') ?? 'لحظات أصيلة تعبق برائحة الحطب والبهارات' }}</span>
            </h2>

            <p class="text-stone-300 text-xs sm:text-sm max-w-2xl mx-auto">
                {{ __('messages.gallery.subtitle') ?? 'شاهد كرم الضيافة وتفاصيل طهي المندي والمظبي والجلسات التراثية' }}
            </p>
        </div>

        {{-- 2. بطاقات الصور التفاعلية --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($images ?? [] as $item)
                <div class="group relative rounded-3xl overflow-hidden cursor-pointer border-2 border-[#E07513]/30 hover:border-[#FFD700] shadow-xl hover:shadow-2xl transition-all duration-300 bg-[#250B08] flex flex-col justify-end h-80 hover:-translate-y-1">
                    <img src="{{ asset($item['image']) }}" alt="{{ $item['title'] }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-108 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

                    <div class="relative z-10 p-5 space-y-1.5 border-t border-[#E07513]/40 bg-gradient-to-t from-[#1C0907] to-transparent">
                        <span class="bg-[#240B08]/90 text-amber-300 text-[11px] font-bold px-3 py-1 rounded-full border border-[#E07513]/40 inline-block mb-1">
                            {{ $item['badge'] }}
                        </span>
                        <h4 class="text-base font-extrabold text-white group-hover:text-amber-300 transition-colors">
                            {{ $item['title'] }}
                        </h4>
                        <p class="text-xs text-stone-300 line-clamp-2">
                            {{ $item['desc'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    {{-- 3. حزام الجنبية اليمانية السفلي المطرز --}}
    <div class="max-w-7xl mx-auto px-4 mt-12">
        <svg class="w-full h-8 sm:h-10 text-amber-500 shadow-xl" viewBox="0 0 1200 40" preserveAspectRatio="none" fill="none">
            <rect x="0" y="0" width="1200" height="40" fill="#210806" rx="6" />
            <rect x="0" y="2" width="1200" height="36" fill="url(#janbiyaPattern)" />
            <rect x="0" y="0" width="1200" height="40" stroke="url(#beltGoldGrad)" stroke-width="2" rx="6" />
        </svg>
    </div>

</section>
