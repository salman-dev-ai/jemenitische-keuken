{{-- أضف في CSS: [x-cloak]{display:none!important} --}}
<div x-data="cookieBanner"
     x-ref="dialog"
     x-show="visible"
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-4"
     tabindex="-1"
     role="dialog"
     aria-modal="false"
     aria-labelledby="cookie-title"
     aria-describedby="cookie-desc"
     class="fixed bottom-4 start-4 z-[900] w-[calc(100%-2rem)] sm:max-w-sm rounded-3xl border border-stone-200/70 bg-[#fbf9f5] p-6 text-stone-800 shadow-2xl focus:outline-none">

    {{-- الرأس: أيقونة + عنوان + زر إغلاق --}}
    <div class="flex items-start justify-between gap-3">
        <div class="flex items-center gap-3">
            <svg class="h-9 w-9 shrink-0 text-[#A84A13]" viewBox="0 0 48 48" fill="none" stroke="currentColor"
                 stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M24 4a20 20 0 1 0 20 20 8 8 0 0 1-8-8 8 8 0 0 1-8-8 4 4 0 0 1-4-4Z"/>
                <circle cx="16" cy="20" r="1.8" fill="currentColor"/>
                <circle cx="24" cy="30" r="1.8" fill="currentColor"/>
                <circle cx="32" cy="34" r="1.8" fill="currentColor"/>
                <circle cx="14" cy="32" r="1.8" fill="currentColor"/>
            </svg>
            <h2 id="cookie-title" class="text-xl font-bold text-stone-900">{{ __('cookies.title') }}</h2>
        </div>

        {{-- زر الإغلاق: يخفي الإشعار دون تسجيل أي قرار، فلا تُفعَّل ملفات غير ضرورية --}}
        <button type="button" @click="dismiss()"
                class="-me-1 -mt-1 shrink-0 rounded-full p-2 text-stone-500 transition hover:bg-stone-200/70 hover:text-stone-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#A84A13] focus-visible:ring-offset-2"
                aria-label="{{ __('cookies.close') }}">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M18 6 6 18M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <p id="cookie-desc" class="mt-3 text-sm leading-relaxed text-stone-600">{{ __('cookies.description') }}</p>

    {{-- خيارات التخصيص --}}
    <fieldset x-show="details" x-cloak x-transition class="mt-4 space-y-3 rounded-xl border border-stone-200 bg-white/70 p-4 text-sm">
        <legend class="sr-only">{{ __('cookies.categories') }}</legend>

        <label class="flex items-center gap-3">
            <input type="checkbox" checked disabled class="h-4 w-4 rounded border-stone-300 accent-[#A84A13]">
            <span class="text-stone-500">{{ __('cookies.necessary') }}</span>
        </label>
        <label class="flex cursor-pointer items-center gap-3">
            <input type="checkbox" x-model="analytics" class="h-4 w-4 rounded border-stone-300 accent-[#A84A13]">
            <span>{{ __('cookies.analytics') }}</span>
        </label>
        <label class="flex cursor-pointer items-center gap-3">
            <input type="checkbox" x-model="marketing" class="h-4 w-4 rounded border-stone-300 accent-[#A84A13]">
            <span>{{ __('cookies.marketing') }}</span>
        </label>
    </fieldset>

    <p x-show="failed" x-cloak role="alert" class="mt-3 text-xs text-red-600">{{ __('cookies.error') }}</p>

    {{-- الرفض والقبول بنفس الحجم واللون (لا أنماط خادعة) --}}
    <div class="mt-5 grid grid-cols-2 gap-3">
        <button type="button" @click="save(false, false)" :disabled="saving"
                class="rounded-lg bg-[#A84A13] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#a37631] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#A84A13] focus-visible:ring-offset-2 disabled:opacity-60">
            {{ __('cookies.reject') }}
        </button>
        <button type="button" @click="save(true, true)" :disabled="saving"
                class="rounded-lg bg-[#A84A13] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#a37631] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#A84A13] focus-visible:ring-offset-2 disabled:opacity-60">
            {{ __('cookies.accept_all') }}
        </button>
    </div>

    <button type="button" x-show="details" x-cloak @click="save(analytics, marketing)" :disabled="saving"
            class="mt-3 w-full rounded-lg border border-[#A84A13] px-4 py-3 text-sm font-semibold text-[#A84A13] transition hover:bg-[#A84A13]/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#A84A13] focus-visible:ring-offset-2 disabled:opacity-60">
        {{ __('cookies.save') }}
    </button>

    {{-- رابط التخصيص --}}
    <div class="mt-5 border-t border-stone-200 pt-4 text-center">
        <button type="button" @click="details = !details" :aria-expanded="details.toString()"
                class="text-sm text-[#A84A13] underline underline-offset-4 transition hover:text-[#8f6529] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#A84A13] focus-visible:ring-offset-2 rounded">
            <span x-show="!details">{{ __('cookies.customize') }}</span>
            <span x-show="details" x-cloak>{{ __('cookies.hide_settings') }}</span>
        </button>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', function () {
        Alpine.data('cookieBanner', function () {
            const consent = window.CookieConsent;
            const DISMISS_KEY = 'cookie-banner-dismissed';

            // هل أُغلق الإشعار سابقاً في هذه الجلسة دون تسجيل قرار؟
            const wasDismissed = function () {
                try {
                    return sessionStorage.getItem(DISMISS_KEY) === '1';
                } catch (e) {
                    return false;
                }
            };

            return {
                visible: !consent.state.decided && !wasDismissed(),
                details: false,
                analytics: consent.state.analytics,
                marketing: consent.state.marketing,
                saving: false,
                failed: false,

                init() {
                    window.addEventListener('cookie-consent-open', () => {
                        this.analytics = consent.state.analytics;
                        this.marketing = consent.state.marketing;
                        this.details = true;
                        this.visible = true;
                        this.$nextTick(() => this.$refs.dialog.focus());
                    });
                },

                // إغلاق الإشعار دون انتظار استجابة الخادم ودون تسجيل أي موافقة.
                dismiss() {
                    try {
                        sessionStorage.setItem(DISMISS_KEY, '1');
                    } catch (e) {
                        /* وضع التصفح الخاص قد يمنع التخزين؛ نتجاهل الخطأ */
                    }
                    this.visible = false;
                },

                async save(analytics, marketing) {
                    if (this.saving) return;
                    this.saving = true;
                    this.failed = false;

                    try {
                        const response = await fetch(@json(route('cookie-consent.store')), {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                            },
                            body: JSON.stringify({ analytics: !!analytics, marketing: !!marketing }),
                        });

                        if (!response.ok) throw new Error('HTTP ' + response.status);

                        const { consent: saved } = await response.json();
                        this.visible = false;
                        consent.update(saved);
                    } catch (e) {
                        this.failed = true;
                    } finally {
                        this.saving = false;
                    }
                },
            };
        });
    });
</script>