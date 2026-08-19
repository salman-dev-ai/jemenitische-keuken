{{-- resources/views/livewire/home-page.blade.php --}}
<div class="space-y-0 text-right font-['Tajawal',sans-serif]" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- 1. HERO SECTION: الترحيب بالضيافة اليمانية وأصالة المندي الملكي --}}
    <section id="home" class="relative min-h-[92vh] flex items-center justify-center bg-gradient-to-b from-[#240B08] via-[#1D0806] to-[#2B0E0A] text-white py-20 px-4 sm:px-6 lg:px-8 overflow-hidden border-b border-[#E07513]/30">

        {{-- خلفية جمالية ونقوش إسلامية --}}
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#E07513_1.5px,transparent_1.5px)] [background-size:24px_24px] pointer-events-none"></div>
        <div class="absolute top-1/4 -right-24 w-96 h-96 bg-[#E07513]/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-12 -left-24 w-96 h-96 bg-[#B85709]/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-5xl mx-auto text-center relative z-10 space-y-7">

            {{-- بادج الترحيب التراثي --}}
            <div class="inline-flex items-center gap-2.5 px-5 py-2 rounded-full bg-white/10 backdrop-blur-md border border-[#E07513]/40 text-amber-300 text-xs sm:text-sm font-bold shadow-xl">
                <span>✨</span>
                <span>{{ __('messages.home.greetingBadge') ?? 'حياكم الله وبياكم في دار الكرم والأصالة' }}</span>
            </div>

            {{-- العنوان الملكي البارز --}}
            <div class="space-y-2">
                <h1 class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-black tracking-tight leading-tight sm:leading-none text-white drop-shadow-lg">
                    <span>{{ __('messages.home.heroTitle') ?? 'المذاق الملكي للمندي اليماني' }}</span>
                    <br />
                    <span class="bg-gradient-to-r from-[#E07513] via-[#F6AA58] to-[#E07513] bg-clip-text text-transparent drop-shadow-sm font-extrabold">
                        {{ __('messages.brand.slogan') ?? 'The Origin Of Mandi | أصل المندي' }}
                    </span>
                </h1>
            </div>

            {{-- النص الوصفي التراثي --}}
            <p class="text-stone-300 text-sm sm:text-lg max-w-3xl mx-auto leading-relaxed font-normal">
                {{ __('messages.home.heroSubtitle') ?? 'على نار حطب السمر وبأسرار البهارات اليافعية والحضرمية المتوارثة منذ مئات السنين.. نقدم لكم في قلب أمستردام تجربة طعام استثنائية تأسر الحواس.' }}
            </p>

            {{-- أزرار الإجراءات السريعة (CTA) --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-2">
                <a href="#reservation" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-[#E07513] via-[#E87E1C] to-[#B85709] hover:from-[#cb660a] hover:to-[#994303] text-white font-extrabold rounded-2xl shadow-xl shadow-[#E07513]/30 hover:shadow-2xl hover:shadow-[#E07513]/45 transition-all flex items-center justify-center gap-3 text-base hover:-translate-y-0.5">
                    <span>📅</span>
                    <span>{{ __('messages.home.ctaReserve') ?? 'احجز طاولتك التفاعلية' }}</span>
                </a>

                <a href="#menu" class="w-full sm:w-auto px-8 py-4 bg-white/10 hover:bg-white/15 text-white font-bold rounded-2xl border border-white/25 hover:border-white/45 transition-all flex items-center justify-center gap-3 text-base backdrop-blur-md">
                    <span>🍽️</span>
                    <span>{{ __('messages.home.ctaMenu') ?? 'استعرض القائمة الملكية' }}</span>
                </a>
            </div>

            {{-- شريط الضيافة المجانية --}}
            <div class="pt-4 max-w-xl mx-auto">
                <div class="bg-[#E07513]/15 border border-[#E07513]/30 rounded-2xl py-2.5 px-4 flex items-center justify-center gap-2.5 text-xs text-amber-200 font-semibold shadow-inner">
                    <span>☕</span>
                    <span>{{ __('messages.home.hospitalityNote') ?? 'خدمة الضيافة والشاي العدني مجاناً لجميع الضيوف' }}</span>
                </div>
            </div>

            {{-- إحصائيات المطعم --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-6 max-w-4xl mx-auto text-stone-200">
                <div class="bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 text-center">
                    <div class="text-2xl sm:text-3xl font-black text-[#E07513]">+25</div>
                    <div class="text-[11px] text-stone-300 font-medium mt-1">عاماً من الخبرة في المندي</div>
                </div>
                <div class="bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 text-center">
                    <div class="text-2xl sm:text-3xl font-black text-[#E07513]">100%</div>
                    <div class="text-[11px] text-stone-300 font-medium mt-1">لحوم بلدية حلال طازجة يومياً</div>
                </div>
                <div class="bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 text-center">
                    <div class="text-2xl sm:text-3xl font-black text-[#E07513]">+18</div>
                    <div class="text-[11px] text-stone-300 font-medium mt-1">نوع بهار يماني نادر ومميز</div>
                </div>
                <div class="bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10 text-center">
                    <div class="text-2xl sm:text-3xl font-black text-[#E07513]">4.9★</div>
                    <div class="text-[11px] text-stone-300 font-medium mt-1">تقييم رضا الضيوف والزوار</div>
                </div>
            </div>

        </div>
    </section>

    {{-- 2. PILLARS SECTION: أركان الأصالة اليمانية الأربعة --}}
    <section class="py-20 bg-[#FDFBF7] text-[#2C1810] relative overflow-hidden border-b border-[#E8DFD3]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center max-w-3xl mx-auto space-y-3 mb-14">
                <div class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-[#E07513]/10 text-[#E07513] text-xs font-bold">
                    <span>🔥</span>
                    <span>أركان الأصالة اليمانية في مطعمنا</span>
                </div>

                <h2 class="text-2xl sm:text-4xl font-extrabold text-[#2C0D0A]">
                    سر النكهة التي لا تُنسى في المطبخ اليمني
                </h2>

                <p class="text-stone-600 text-xs sm:text-sm">
                    نلتزم بأدق تفاصيل الطهي التقليدي دون أي مساومة على الجودة والنكهة
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- ركن 1: براميل الحطب --}}
                <div class="bg-white rounded-3xl p-6 border border-[#E8DFD3] hover:border-[#E07513]/40 shadow-xs hover:shadow-xl transition-all duration-300 space-y-4 group">
                    <div class="w-14 h-14 rounded-2xl bg-[#FAF4ED] text-[#E07513] border border-[#E07513]/20 flex items-center justify-center text-2xl group-hover:bg-[#E07513] group-hover:text-white transition-colors">
                        🪵
                    </div>
                    <h3 class="text-base font-extrabold text-[#2C0D0A] group-hover:text-[#E07513] transition-colors">
                        براميل الحطب تحت الأرض
                    </h3>
                    <p class="text-xs text-stone-600 leading-relaxed">
                        طهي بطيء للحوم والأرز داخل براميل طينية محكمة الإغلاق لأكثر من 4 ساعات لتذوب اللحوم بنكهة مدخنة ساحرة.
                    </p>
                </div>

                {{-- ركن 2: المقلى الصنعاني --}}
                <div class="bg-white rounded-3xl p-6 border border-[#E8DFD3] hover:border-[#E07513]/40 shadow-xs hover:shadow-xl transition-all duration-300 space-y-4 group">
                    <div class="w-14 h-14 rounded-2xl bg-[#FAF4ED] text-[#E07513] border border-[#E07513]/20 flex items-center justify-center text-2xl group-hover:bg-[#E07513] group-hover:text-white transition-colors">
                        🍲
                    </div>
                    <h3 class="text-base font-extrabold text-[#2C0D0A] group-hover:text-[#E07513] transition-colors">
                        فخاريات المقلى الصنعاني
                    </h3>
                    <p class="text-xs text-stone-600 leading-relaxed">
                        أوانٍ منحوتة من الحجر البركاني تحتفظ بالحرارة الشديدة لتقدم الفحسة والسلتة وهي تغلي أمامك مع رغوة الحلبة.
                    </p>
                </div>

                {{-- ركن 3: المظبي على الصوان --}}
                <div class="bg-white rounded-3xl p-6 border border-[#E8DFD3] hover:border-[#E07513]/40 shadow-xs hover:shadow-xl transition-all duration-300 space-y-4 group">
                    <div class="w-14 h-14 rounded-2xl bg-[#FAF4ED] text-[#E07513] border border-[#E07513]/20 flex items-center justify-center text-2xl group-hover:bg-[#E07513] group-hover:text-white transition-colors">
                        🔥
                    </div>
                    <h3 class="text-base font-extrabold text-[#2C0D0A] group-hover:text-[#E07513] transition-colors">
                        أحجار الصوان للمظبي
                    </h3>
                    <p class="text-xs text-stone-600 leading-relaxed">
                        شواء الدجاج واللحوم فوق حجارة الصوان البركانية الملتهبة على الجمر للحصول على قرمشة ونكهة لا مثيل لها.
                    </p>
                </div>

                {{-- ركن 4: البن والعسل --}}
                <div class="bg-white rounded-3xl p-6 border border-[#E8DFD3] hover:border-[#E07513]/40 shadow-xs hover:shadow-xl transition-all duration-300 space-y-4 group">
                    <div class="w-14 h-14 rounded-2xl bg-[#FAF4ED] text-[#E07513] border border-[#E07513]/20 flex items-center justify-center text-2xl group-hover:bg-[#E07513] group-hover:text-white transition-colors">
                        🍯
                    </div>
                    <h3 class="text-base font-extrabold text-[#2C0D0A] group-hover:text-[#E07513] transition-colors">
                        البن والعسل الدوعني
                    </h3>
                    <p class="text-xs text-stone-600 leading-relaxed">
                        نستورد العسل الدوعني الصافي والبن الخولاني اليافعي مباشرة من مزارع اليمن العريقة لتقديم أرقى الحلويات والمشروبات.
                    </p>
                </div>
            </div>

        </div>
    </section>

    {{-- 3. MAJLIS SECTION: الديوان اليماني والخصوصية --}}
    <section class="py-20 bg-gradient-to-br from-[#2E0F0B] via-[#240B08] to-[#1A0604] text-white relative overflow-hidden border-b border-[#E07513]/25">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

            <div class="lg:col-span-6 space-y-6">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white/10 text-amber-300 text-xs font-bold border border-white/15">
                    <span>👥</span>
                    <span>الضيافة والخصوصية العائلية</span>
                </div>

                <h2 class="text-2xl sm:text-4xl font-extrabold leading-tight text-white">
                    أجواء تجمع بين الديوان اليماني والأناقة الأوروبية
                </h2>

                <p class="text-stone-300 text-sm leading-relaxed">
                    اختر الجلسة التي تناسبك: جلسات ديوان تراثية أرضية فاخرة بالمساند المطرزة، أو طاولات ملكية راقية للعائلات والمناسبات.
                </p>

                <div class="space-y-3 pt-2">
                    <div class="flex items-start gap-3 bg-white/5 p-3.5 rounded-2xl border border-white/10 text-xs font-semibold">
                        <span class="text-[#E07513]">✓</span>
                        <span>جلسات ديوان أرضية منعزلة للعائلات مع خصوصية تامة.</span>
                    </div>
                    <div class="flex items-start gap-3 bg-white/5 p-3.5 rounded-2xl border border-white/10 text-xs font-semibold">
                        <span class="text-[#E07513]">✓</span>
                        <span>طاولات ملكية فسيحة مجهزة للمناسبات والاجتماعات الراقية.</span>
                    </div>
                    <div class="flex items-start gap-3 bg-white/5 p-3.5 rounded-2xl border border-white/10 text-xs font-semibold">
                        <span class="text-[#E07513]">✓</span>
                        <span>تعطير وتبخير باللبان والعود اليمني بعد كل وجبة مجاناً.</span>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="#reservation" class="inline-flex items-center gap-2.5 px-6 py-3.5 bg-[#E07513] hover:bg-[#c2620a] text-white font-bold rounded-2xl text-xs sm:text-sm shadow-xl shadow-[#E07513]/25 transition-all">
                        <span>احجز جلستك المفضلة مسبقاً ←</span>
                    </a>
                </div>
            </div>

            <div class="lg:col-span-6">
                <div class="bg-gradient-to-tr from-[#1B0705] to-[#34110C] p-6 sm:p-8 rounded-3xl border border-[#E07513]/30 shadow-2xl text-center space-y-4">
                    <h3 class="text-xl font-bold text-[#E07513]">المطبخ اليمني - Jemenitische Keuken</h3>
                    <p class="text-xs text-amber-200 italic">"الضيافة في اليمن ليست مجرد طعام، بل ميثاق كرم ومحبة يتوارثه الأبناء عن الأجداد"</p>
                </div>
            </div>

        </div>
    </section>

</div>
