<div class="bg-white rounded-3xl p-6 md:p-10 shadow-2xl border border-[#D47716]/15 relative overflow-hidden text-right font-sans" dir="rtl">

    {{-- الشريط الجمالي العلوي --}}
    <div class="absolute top-0 right-0 left-0 h-1.5 bg-gradient-to-l from-[#D47716] via-[#E9963F] to-[#3E1F15]"></div>

    {{-- رأس النموذج والترحيب ومؤشر الطاولات المتوفرة --}}
    <div class="mb-8 border-b border-stone-100 pb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="w-8 h-8 rounded-lg bg-[#D47716]/10 text-[#D47716] flex items-center justify-center font-bold text-sm">🍽️</span>
                <span class="text-xs font-bold text-[#D47716] tracking-wider uppercase">حجز إلكتروني فوري ومباشر</span>
            </div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-[#3E1F15] tracking-tight">احجز طاولتك التفاعلية</h2>
            <p class="text-stone-500 text-sm mt-1">اختر عدد الضيوف والوقت والموقع المفضل وسنجهز لك الطاولة المثالية.</p>
        </div>

        {{-- مؤشر عدد الطاولات المتاحة الحية --}}
        <div class="flex items-center gap-2 bg-[#FAF6F0] px-4 py-2 rounded-2xl border border-[#E8DFD3] shrink-0 self-start md:self-auto">
            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
            <span class="text-xs font-bold text-[#3E1F15]">
                {{ count($this->availableTables) }} طاولة متاحة في هذا التوقيت
            </span>
        </div>
    </div>

    {{-- بطاقة النجاح الفندقية الرقمية --}}
    @if ($successMessage)
        <div class="bg-gradient-to-br from-[#3E1F15] to-[#24110B] text-white rounded-3xl p-6 md:p-8 shadow-2xl mb-8 relative overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-2xl font-bold shadow-lg">✓</div>
                    <div>
                        <h4 class="font-extrabold text-lg text-white">{{ $successMessage }}</h4>
                        <p class="text-xs text-stone-300">أهلاً بك يا <strong>{{ $customer_name }}</strong>، تم تأكيد حجزك بنجاح.</p>
                    </div>
                </div>

                {{-- الكود المرجعي للحجز --}}
                <div class="bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-xl border border-white/15">
                    <div class="text-[10px] text-stone-300">كود الحجز المرجعي</div>
                    <div class="font-mono text-base font-black text-amber-300 tracking-widest">{{ $referenceCode }}</div>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 text-stone-200 text-xs">
                <div>
                    <span class="text-stone-400 block text-[11px]">التاريخ</span>
                    <span class="font-bold text-white text-sm">{{ $reservation_date }}</span>
                </div>
                <div>
                    <span class="text-stone-400 block text-[11px]">الوقت</span>
                    <span class="font-bold text-white text-sm">{{ $reservation_time }}</span>
                </div>
                <div>
                    <span class="text-stone-400 block text-[11px]">عدد الضيوف</span>
                    <span class="font-bold text-white text-sm">{{ $party_size }} أشخاص</span>
                </div>
                <div>
                    <span class="text-stone-400 block text-[11px]">رقم الطاولة</span>
                    <span class="font-bold text-white text-sm">طاولة #{{ $this->selectedTable?->table_number ?? $table_id }}</span>
                </div>
            </div>
        </div>
    @endif

    {{-- رسالة التنبيه في حالة وجود خطأ --}}
    @if ($errorMessage)
        <div class="bg-rose-50 border-r-4 border-rose-500 p-4 rounded-2xl mb-6 text-rose-800 text-sm flex items-center gap-3">
            <span class="text-rose-600 text-xl font-bold">⚠️</span>
            <div>
                <strong class="font-bold block">تنبيه في إدخال البيانات</strong>
                <span>{{ $errorMessage }}</span>
            </div>
        </div>
    @endif

    {{-- نموذج الحجز التفاعلي --}}
    <form wire:submit.prevent="submitReservation" class="space-y-8">

        {{-- الخطوة 1: الأشخاص والوقت --}}
        <div class="space-y-3">
            <div class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-[#3E1F15]">
                <span class="w-5 h-5 rounded-full bg-[#3E1F15] text-white flex items-center justify-center text-[10px]">1</span>
                <span>بيانات الحضور والتوقيت</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- عدد الضيوف مع أزرار سريعة --}}
                <div class="bg-[#FAF7F2] p-4 rounded-2xl border border-stone-200 space-y-2">
                    <div class="flex justify-between items-center text-xs font-bold text-stone-700">
                        <label>عدد الأشخاص (الضيوف)</label>
                        <span class="text-[#D47716] font-black">{{ $party_size }} ضيوف</span>
                    </div>

                    <div class="flex items-center gap-1">
                        @foreach([1, 2, 4, 6, 8] as $size)
                            <button type="button" wire:click="$set('party_size', {{ $size }})"
                                    class="flex-1 py-1.5 text-xs font-bold rounded-lg transition-all {{ $party_size == $size ? 'bg-[#D47716] text-white shadow-xs' : 'bg-white text-stone-700 hover:bg-stone-100 border border-stone-200' }}">
                                {{ $size }}
                            </button>
                        @endforeach
                    </div>

                    <input type="number" wire:model.live.debounce.300ms="party_size" min="1" max="20"
                           class="w-full bg-white rounded-xl border-stone-200 text-sm font-semibold focus:border-[#D47716] focus:ring-2 focus:ring-[#D47716]/20">
                    @error('party_size') <span class="text-xs text-rose-500 block">{{ $message }}</span> @enderror
                </div>

                {{-- التاريخ مع أزرار اختيار سريعة --}}
                <div class="bg-[#FAF7F2] p-4 rounded-2xl border border-stone-200 space-y-2">
                    <label class="block text-xs font-bold text-stone-700">تاريخ الحجز</label>
                    <input type="date" wire:model.live="reservation_date" min="{{ now()->toDateString() }}"
                           class="w-full bg-white rounded-xl border-stone-200 text-sm font-semibold focus:border-[#D47716] focus:ring-2 focus:ring-[#D47716]/20">
                    @error('reservation_date') <span class="text-xs text-rose-500 block">{{ $message }}</span> @enderror

                    <div class="flex gap-1 text-[11px] pt-1">
                        <button type="button" wire:click="$set('reservation_date', '{{ now()->toDateString() }}')" class="flex-1 py-0.5 bg-white border rounded text-stone-600">اليوم</button>
                        <button type="button" wire:click="$set('reservation_date', '{{ now()->addDay()->toDateString() }}')" class="flex-1 py-0.5 bg-white border rounded text-stone-600">غداً</button>
                    </div>
                </div>

                {{-- وقت الحضور --}}
                <div class="bg-[#FAF7F2] p-4 rounded-2xl border border-stone-200 space-y-2">
                    <label class="block text-xs font-bold text-stone-700">وقت الحضور المفضل</label>
                    <input type="time" wire:model.live="reservation_time"
                           class="w-full bg-white rounded-xl border-stone-200 text-sm font-semibold focus:border-[#D47716] focus:ring-2 focus:ring-[#D47716]/20">
                    @error('reservation_time') <span class="text-xs text-rose-500 block">{{ $message }}</span> @enderror
                    <span class="text-[11px] text-stone-500 block">مدة الجلسة المتاحة: 90 دقيقة</span>
                </div>
            </div>
        </div>

        {{-- الخطوة 2: اختيار الطاولة التفاعلي عبر بطاقات بصرية --}}
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-[#3E1F15]">
                    <span class="w-5 h-5 rounded-full bg-[#3E1F15] text-white flex items-center justify-center text-[10px]">2</span>
                    <span>اختيار الطاولة وموقع الجلوس</span>
                </div>
            </div>

            {{-- شبكة بطاقات الطاولات البصرية --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 bg-[#FAF6F0] p-4 rounded-2xl border border-[#E8DFD3]">
                @forelse($this->availableTables as $table)
                    <div wire:key="table-{{ $table->id }}"
                         wire:click="$set('table_id', {{ $table->id }})"
                         class="p-3.5 rounded-xl border transition-all cursor-pointer select-none relative {{ $table_id == $table->id ? 'bg-[#FFFDF9] border-[#D47716] ring-2 ring-[#D47716]/30 shadow-md' : 'bg-white border-stone-200 hover:border-[#D47716]/50 shadow-xs' }}">
                        <div class="flex items-start justify-between gap-2 mb-1.5">
                            <div class="flex items-center gap-2">
                                <span class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs {{ $table_id == $table->id ? 'bg-[#D47716] text-white' : 'bg-[#3E1F15] text-white' }}">
                                    #{{ $table->table_number }}
                                </span>
                                <div>
                                    <h5 class="font-bold text-xs text-[#3E1F15]">طاولة {{ $table->table_number }}</h5>
                                    <span class="text-[11px] text-stone-500">سعة: {{ $table->capacity }} أشخاص</span>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $table_id == $table->id ? 'bg-[#D47716] text-white' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                {{ $table_id == $table->id ? 'مختارة ✓' : 'متاحة' }}
                            </span>
                        </div>
                        <div class="text-[11px] text-stone-500 pt-1 border-t border-stone-100 flex justify-between">
                            <span>{{ $table->location ?? 'الصالة الرئيسية' }}</span>
                            <span class="text-[#D47716] font-semibold">تأكيد فوري</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-6 text-stone-500 text-sm">
                        لا توجد طاولات شاغرة لعدد {{ $party_size }} أشخاص في هذا الوقت. جرب وقتاً أو تاريخاً آخر.
                    </div>
                @endforelse
            </div>
            @error('table_id') <span class="text-xs text-rose-500 block">{{ $message }}</span> @enderror
        </div>

        {{-- الخطوة 3: بيانات العميل والطلبات الخاصة --}}
        <div class="space-y-4 pt-4 border-t border-stone-100">
            <div class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-[#3E1F15]">
                <span class="w-5 h-5 rounded-full bg-[#3E1F15] text-white flex items-center justify-center text-[10px]">3</span>
                <span>بيانات التواصل والطلبات الخاصة</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-stone-700 mb-1.5">اسمك الكامل <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="customer_name" placeholder="أدخل اسمك الكريم"
                           class="w-full bg-white rounded-xl border-stone-200 text-sm focus:border-[#D47716] focus:ring-2 focus:ring-[#D47716]/20">
                    @error('customer_name') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-stone-700 mb-1.5">رقم الهاتف للتأكيد <span class="text-rose-500">*</span></label>
                    <input type="tel" wire:model="customer_phone" placeholder="+966 5X XXX XXXX" dir="ltr"
                           class="w-full bg-white rounded-xl border-stone-200 text-sm font-mono focus:border-[#D47716] focus:ring-2 focus:ring-[#D47716]/20">
                    @error('customer_phone') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-stone-700 mb-1.5">ملاحظات أو طلبات خاصة (اختياري)</label>
                <textarea wire:model="special_requests" rows="2" placeholder="هل لديك مناسبة خاصة؟ كراسي أطفال أو ترتيبات محددة؟"
                          class="w-full bg-white rounded-xl border-stone-200 text-sm focus:border-[#D47716] focus:ring-2 focus:ring-[#D47716]/20"></textarea>
            </div>
        </div>

        {{-- زر الإرسال مع مؤشر التحميل المتفاعل --}}
        <div class="pt-2">
            <button type="submit" wire:loading.attr="disabled"
                    class="w-full bg-gradient-to-r from-[#D47716] via-[#DE8325] to-[#B8630F] hover:from-[#c2680e] hover:to-[#9a4f08] text-white font-extrabold py-4 px-6 rounded-2xl shadow-xl shadow-[#D47716]/20 hover:shadow-2xl transition-all duration-300 flex items-center justify-center gap-3 cursor-pointer disabled:opacity-50">
                <span wire:loading.remove class="flex items-center gap-2">
                    <span>✨</span>
                    <span>إرسال وتأكيد الحجز التفاعلي</span>
                </span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>جاري معالجة الحجز والتحقق من التوفر...</span>
                </span>
            </button>
        </div>

    </form>
</div>
