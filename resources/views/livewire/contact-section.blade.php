{{-- resources/views/livewire/contact-section.blade.php --}}
<section id="contact" class="py-20 bg-[#FAF6F0] text-[#2C1810] relative overflow-hidden border-b border-[#E8DFD3] font-['Tajawal',sans-serif]" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="text-center max-w-3xl mx-auto space-y-3 mb-14">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#E07513]/10 text-[#E07513] text-xs font-bold">
                <span>✨</span>
                <span>{{ __('messages.contact.badge') ?? 'تواصل معنا والضيافة في انتظاركم' }}</span>
            </div>

            <h2 class="text-2xl sm:text-4xl md:text-5xl font-black text-[#2C0D0A]">
                {{ __('messages.contact.title') ?? 'نسعد بزيارتكم واستقبال استفساراتكم' }}
            </h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- نموذج التواصل --}}
            <div class="lg:col-span-6 bg-white p-6 sm:p-8 rounded-3xl border border-[#E8DFD3] shadow-lg text-right space-y-6">
                <h3 class="text-lg font-extrabold text-[#2C0D0A]">أرسل لنا رسالة مباشرة</h3>

                @if (session()->has('contact_success'))
                    <div class="bg-emerald-50 border-r-4 border-emerald-500 p-4 rounded-2xl">
                        <p class="text-xs font-bold text-emerald-800">{{ session('contact_success') }}</p>
                    </div>
                @endif

                <form wire:submit.prevent="submitMessage" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-stone-700 mb-1.5">الاسم الكامل *</label>
                        <input type="text" wire:model.defer="name" required class="w-full px-4 py-3 rounded-xl bg-stone-50 border border-stone-200 text-xs">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1.5">البريد الإلكتروني *</label>
                            <input type="email" wire:model.defer="email" required class="w-full px-4 py-3 rounded-xl bg-stone-50 border border-stone-200 text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1.5">رقم الهاتف / الواتساب</label>
                            <input type="tel" wire:model.defer="phone" class="w-full px-4 py-3 rounded-xl bg-stone-50 border border-stone-200 text-xs">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-700 mb-1.5">نوع الاستفسار</label>
                        <select wire:model.defer="subject" class="w-full px-4 py-3 rounded-xl bg-stone-50 border border-stone-200 text-xs">
                            <option value="inquiry">استفسار عام عن المطعم والمنيو</option>
                            <option value="event">حجز مناسبة خاصة أو ديوان عائلي كبير</option>
                            <option value="catering">خدمات التموين والولائم الخارجية</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-700 mb-1.5">رسالتك أو طلبك الخاص *</label>
                        <textarea rows="4" wire:model.defer="message" required class="w-full px-4 py-3 rounded-xl bg-stone-50 border border-stone-200 text-xs resize-none"></textarea>
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-[#E07513] to-[#B85709] text-white font-extrabold rounded-xl text-xs sm:text-sm shadow-md">
                        إرسال الرسالة الآن ←
                    </button>
                </form>
            </div>

            {{-- تفاصيل العناوين والخريطة --}}
            <div class="lg:col-span-6 space-y-6 text-right">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-white p-5 rounded-3xl border border-[#E8DFD3] shadow-xs space-y-2">
                        <h4 class="font-extrabold text-sm text-[#2C0D0A]">العنوان والموقع</h4>
                        <p class="text-xs text-stone-600">Damrak 45, Amsterdam, Netherlands</p>
                    </div>
                    <div class="bg-white p-5 rounded-3xl border border-[#E8DFD3] shadow-xs space-y-2">
                        <h4 class="font-extrabold text-sm text-[#2C0D0A]">ساعات العمل</h4>
                        <p class="text-xs text-stone-600">يومياً من 12:00 حتى 23:30</p>
                    </div>
                </div>

                <div class="bg-white rounded-3xl overflow-hidden border border-[#E8DFD3] shadow-md">
                    <iframe src="https://maps.google.com/maps?q=Damrak%2045%20Amsterdam&t=&z=15&ie=UTF8&iwloc=&output=embed"
                            class="w-full h-56 border-0" loading="lazy"></iframe>
                </div>
            </div>

        </div>
    </div>
</section>