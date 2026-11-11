{{--
    قسم التواصل (تصميم جديد): خلفية كاملة للقسم + بطاقة نموذج + بطاقة خريطة + بطاقة اتصال/بريد.
    لا يُرسل الموقع الرسالة بنفسه؛ يفتح العميل واتساب ليضغط الإرسال.
    صورة الخلفية: public/images/contact/image.webp
--}}
@php
    use Illuminate\Support\Facades\Lang;

    // ترجمة مع نص افتراضي إن لم يوجد المفتاح في ملفات اللغة
    $t = fn (string $key, string $default) => Lang::has($key) ? __($key) : $default;

    $s = \App\Models\RestaurantSetting::current();
    $address = trim((string) $s->address);
    $cityLine = implode(', ', array_filter([trim((string) $s->city), trim((string) $s->postal_code)]));
    $maps = trim((string) $s->google_maps_link);
    $whatsapp = trim((string) $s->whatsapp);
    $phone = trim((string) $s->phone);
    $email = trim((string) $s->email);

    $callNumber = $phone !== '' ? $phone : $whatsapp;
    $fullAddress = trim($address . ($address !== '' && $cityLine !== '' ? '، ' : '') . $cityLine);

    // الخريطة المضمّنة + رابط الاتجاهات
    // الأولوية: رابط جوجل ماب الذي أدخله المدير (يُفكّ ويُخزَّن)، وإلا نستخدم العنوان النصي
    $mapData = \App\Support\GoogleMapsLink::resolve($maps);
    $mapQuery = $fullAddress !== '' ? $fullAddress : (string) config('app.name');
    // (لا نضع رابط جوجل ماب داخل q= لأنه يصبح نص بحث وليس موقعاً)

    if ($mapData) {
        $mapEmbed = $mapData['embed'];
        $directions = $maps; // الرابط الأصلي كما أدخله المدير
    } else {
        $mapEmbed = 'https://www.google.com/maps?q=' . urlencode($mapQuery) . '&output=embed';
        $directions = $maps !== '' ? $maps : 'https://www.google.com/maps/dir/?api=1&destination=' . urlencode($mapQuery);
    }
    $isRtl = app()->getLocale() === 'ar';
@endphp

<section id="contact" dir="{{ $isRtl ? 'rtl' : 'ltr' }}"
    class="relative isolate overflow-hidden bg-[#FBF5EA] py-16 text-[#3A0D0D] sm:py-20"
    style="background-image: url('{{ asset('images/contact/image.webp') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">

    {{-- طبقة خفيفة فوق الخلفية لضمان وضوح القراءة مع إبقاء الصورة ظاهرة --}}
    <div class="pointer-events-none absolute inset-0 -z-10 bg-gradient-to-b from-[#FBF5EA]/55 via-[#FBF5EA]/25 to-[#FBF5EA]/55"></div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- الترويسة --}}
        <header class="mb-10 text-center lg:mb-14">
            <div class="flex items-center justify-center gap-4">
                <span class="h-px w-12 bg-[#C8892B] sm:w-16"></span>
                <p class="text-sm font-bold text-[#C8892B] sm:text-base">{{ __('messages.contact.badge') }}</p>
                <span class="h-px w-12 bg-[#C8892B] sm:w-16"></span>
            </div>
            <h2 class="mt-4 text-3xl font-black leading-tight text-[#4A0E0E] sm:text-4xl lg:text-5xl">
                {{ $t('messages.contact.title', 'نسعد بزيارتكم واستقبال استفساراتكم') }}
            </h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-7 text-[#5C3A33] sm:text-base">
                {{ $t('messages.contact.subtitle', 'فريقنا جاهز للإجابة على جميع استفساراتكم، ونرحب بزيارتكم في مطعمنا.') }}
            </p>
        </header>

        <div class="grid items-start gap-6 lg:grid-cols-2 lg:gap-8">

            {{-- ===== العمود الأول: الخريطة + الاتصال/البريد ===== --}}
            <div class="space-y-6">

                {{-- بطاقة الخريطة --}}
                <div class="overflow-hidden rounded-3xl border border-[#B8862B]/25 bg-white/90 p-3 shadow-xl shadow-[#4A0E0E]/10 backdrop-blur-sm">
                    <div class="relative h-64 overflow-hidden rounded-2xl sm:h-80 lg:h-[26rem]">
                        <iframe src="{{ $mapEmbed }}" title="{{ $t('footer.view_map', 'عرض الخريطة') }}"
                            class="h-full w-full border-0" loading="lazy" allowfullscreen
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>

                    <div class="mt-3 flex flex-wrap items-center justify-between gap-3 rounded-2xl bg-white px-3 py-3 sm:px-4">
                        <div class="flex items-center gap-3">
                            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-[#F6E9D3] text-[#4A0E0E]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5Z"/></svg>
                            </span>
                            <div>
                                <h3 class="font-black text-[#3A0D0D]">{{ __('footer.default_name') }} · {{ __('footer.brand_line') }}</h3>
                                @if ($fullAddress !== '')
                                    <p class="text-sm text-[#5C3A33]">{{ $fullAddress }}</p>
                                @else
                                    <p class="text-sm text-[#5C3A33]">{{ __('footer.address_unavailable') }}</p>
                                @endif
                            </div>
                        </div>

                        <a href="{{ $directions }}" target="_blank" rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 rounded-xl bg-[#E8A93A] px-5 py-2.5 text-sm font-black text-[#3A0D0D] shadow-sm transition hover:bg-[#D99A2B]">
                            <svg class="h-4 w-4 {{ $isRtl ? '-scale-x-100' : '' }}" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M21 3 3 10.5l7 2.5 2.5 7L21 3Z"/></svg>
                            {{ $t('messages.contact.directions', 'الاتجاهات') }}
                        </a>
                    </div>
                </div>

                {{-- بطاقة الاتصال والبريد --}}
                <div class="grid overflow-hidden rounded-3xl border border-[#B8862B]/25 bg-white/90 shadow-xl shadow-[#4A0E0E]/10 backdrop-blur-sm sm:grid-cols-2 sm:divide-x sm:divide-[#B8862B]/20 rtl:sm:divide-x-reverse">

                    {{-- الهاتف --}}
                    <div class="p-6">
                        <div class="flex items-center gap-3">
                            <span class="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-[#F6E9D3] text-[#4A0E0E]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.25 11.4 11.4 0 0 0 3.6.57 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.45.57 3.6a1 1 0 0 1-.25 1l-2.22 2.2Z"/></svg>
                            </span>
                            <h3 class="text-lg font-black">{{ $t('messages.contact.call_us', 'اتصل بنا') }}</h3>
                        </div>
                        <div class="mt-4">
                            @if ($callNumber !== '')
                                <a dir="ltr" href="tel:{{ preg_replace('/[^\d+]/', '', $callNumber) }}"
                                    class="inline-block text-sm font-black text-[#4A0E0E] hover:text-[#C8892B]">{{ $callNumber }}</a>
                            @else
                                <p class="text-sm font-bold">{{ $t('messages.contact.phone_label', 'رقم الهاتف') }}</p>
                            @endif
                            <p class="mt-1 text-sm text-[#5C3A33]">{{ $t('messages.contact.phone_hint', 'متاح خلال ساعات العمل') }}</p>
                        </div>
                    </div>

                    {{-- البريد --}}
                    <div class="border-t border-[#B8862B]/20 p-6 sm:border-t-0">
                        <div class="flex items-center gap-3">
                            <span class="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-[#F6E9D3] text-[#4A0E0E]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                            </span>
                            <h3 class="text-lg font-black">{{ $t('messages.contact.write_us', 'راسلنا') }}</h3>
                        </div>
                        <div class="mt-4">
                            @if ($email !== '')
                                <a dir="ltr" href="mailto:{{ $email }}"
                                    class="inline-block break-all text-sm font-black text-[#4A0E0E] hover:text-[#C8892B]">{{ $email }}</a>
                            @else
                                <p class="text-sm font-bold">{{ $t('messages.contact.email_label', 'البريد الإلكتروني') }}</p>
                            @endif
                            <p class="mt-1 text-sm text-[#5C3A33]">{{ $t('messages.contact.email_hint', 'سنرد عليكم في أقرب وقت') }}</p>
                        </div>
                    </div>

                    @if ($callNumber === '' && $email === '')
                        <p class="border-t border-[#B8862B]/20 p-4 text-center text-sm text-[#5C3A33] sm:col-span-2">{{ __('footer.contact_unavailable') }}</p>
                    @endif
                </div>
            </div>

            {{-- ===== العمود الثاني: النموذج ===== --}}
            <div class="rounded-3xl border border-[#B8862B]/25 bg-white/95 p-6 shadow-xl shadow-[#4A0E0E]/10 backdrop-blur-sm sm:p-8">

                <div class="mb-6 flex items-start gap-4">
                    <span class="grid h-14 w-14 shrink-0 place-items-center rounded-full bg-[#F6E9D3] text-[#4A0E0E]">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                    </span>
                    <div>
                        <h3 class="text-2xl font-black text-[#4A0E0E]">{{ __('messages.contact.form.title') }}</h3>
                        <p class="mt-1 text-sm text-[#5C3A33]">{{ $t('messages.contact.form.subtitle', 'مساعدتكم تهمنا، راسلونا وسنرد عليكم في أقرب وقت.') }}</p>
                    </div>
                </div>

                @unless ($whatsappAvailable)
                    <p class="mb-4 rounded-xl bg-amber-50 p-3 text-sm text-amber-800">
                        {{ __('messages.contact.form.whatsapp_unavailable') }}
                    </p>
                @endunless

                @error('general')
                    <p class="mb-3 text-sm font-bold text-red-600">{{ $message }}</p>
                @enderror

                <form class="space-y-4" @submit.prevent>
                    <div>
                        <label class="mb-1.5 block text-sm font-bold">{{ __('messages.contact.form.name') }} <span class="text-red-600">*</span></label>
                        <input type="text" wire:model="name" placeholder="{{ $t('messages.contact.form.name_placeholder', 'أدخل اسمك الكامل') }}"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm placeholder:text-stone-400 focus:border-[#C8892B] focus:outline-none focus:ring-2 focus:ring-[#C8892B]/30">
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-bold">{{ __('messages.contact.form.email') }} <span class="text-red-600">*</span></label>
                        <input type="email" wire:model="email" placeholder="{{ $t('messages.contact.form.email_placeholder', 'أدخل بريدك الإلكتروني') }}"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm placeholder:text-stone-400 focus:border-[#C8892B] focus:outline-none focus:ring-2 focus:ring-[#C8892B]/30">
                        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-bold">{{ __('messages.contact.form.whatsapp') }}</label>
                        <input type="text" wire:model="whatsapp" placeholder="{{ $t('messages.contact.form.whatsapp_placeholder', 'أدخل رقم الهاتف أو واتساب') }}"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm placeholder:text-stone-400 focus:border-[#C8892B] focus:outline-none focus:ring-2 focus:ring-[#C8892B]/30">
                        @error('whatsapp') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-bold">{{ __('messages.contact.form.subject') }}</label>
                        <select wire:model="subject"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm focus:border-[#C8892B] focus:outline-none focus:ring-2 focus:ring-[#C8892B]/30">
                            <option value="inquiry">{{ __('messages.contact.form.subjects.inquiry') }}</option>
                            <option value="event">{{ __('messages.contact.form.subjects.event') }}</option>
                            <option value="catering">{{ __('messages.contact.form.subjects.catering') }}</option>
                        </select>
                        @error('subject') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-bold">{{ __('messages.contact.form.message') }} <span class="text-red-600">*</span></label>
                        <textarea rows="4" wire:model="message" placeholder="{{ $t('messages.contact.form.message_placeholder', 'اكتب رسالتك هنا...') }}"
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm placeholder:text-stone-400 focus:border-[#C8892B] focus:outline-none focus:ring-2 focus:ring-[#C8892B]/30"></textarea>
                        @error('message') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- فتح تبويب فارغ بشكل متزامن (نقرة المستخدم) ثم توجيهه لرابط واتساب --}}
                    <button type="button"
                        @click="const w = window.open('', '_blank'); $wire.submit().then(url => { url ? (w.location = url) : w.close(); })"
                        @disabled(! $whatsappAvailable)
                        class="flex w-full items-center justify-center gap-3 rounded-xl bg-[#5E0F0F] px-5 py-3.5 font-black text-white shadow-lg shadow-[#5E0F0F]/25 transition hover:bg-[#470A0A] disabled:cursor-not-allowed disabled:opacity-50">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2a9.9 9.9 0 0 0-8.5 14.9L2 22l5.25-1.38A9.9 9.9 0 1 0 12.04 2Zm0 18.06a8.2 8.2 0 0 1-4.18-1.14l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 1 1 6.97 3.85Zm4.5-6.14c-.25-.12-1.46-.72-1.69-.8-.23-.09-.39-.12-.56.12-.16.25-.64.8-.78.97-.14.16-.29.18-.54.06a6.7 6.7 0 0 1-3.3-2.88c-.25-.43.25-.4.72-1.34.08-.16.04-.3-.02-.42-.06-.12-.56-1.35-.76-1.85-.2-.48-.41-.42-.56-.43h-.48c-.16 0-.43.06-.66.3-.22.25-.86.84-.86 2.06s.88 2.38 1 2.55c.12.16 1.73 2.64 4.19 3.7.59.26 1.04.41 1.4.52.59.19 1.12.16 1.54.1.47-.07 1.46-.6 1.66-1.17.2-.58.2-1.07.14-1.17-.06-.1-.22-.16-.47-.28Z"/></svg>
                        {{ __('messages.contact.form.submit_whatsapp') }}
                    </button>

                    <p class="flex items-center justify-center gap-2 text-center text-xs text-stone-500">
                        <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17 9V7a5 5 0 0 0-10 0v2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2Zm-8-2a3 3 0 0 1 6 0v2H9V7Z"/></svg>
                        {{ __('messages.contact.form.whatsapp_note') }}
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>