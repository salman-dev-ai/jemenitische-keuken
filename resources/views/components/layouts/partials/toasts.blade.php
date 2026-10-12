{{--
    نظام إشعارات المتصفح.
    يستمع لحدث notify على النافذة ويعرض بطاقة نجاح عبر Alpine
    مع شريط تقدم يفرغ ثم تُزال البطاقة محلياً.
--}}
{{-- إضافة الإشعار وإخفاؤه يتمان في المتصفح فقط --}}
<div x-data="{
    toasts: [],
    add(toast) {
        const id = Date.now();
        this.toasts.push({ id, ...toast, visible: false });
        this.$nextTick(() => {
            const item = this.toasts.find((entry) => entry.id === id);
            if (item) item.visible = true;
        });
        setTimeout(() => this.remove(id), toast.duration ?? 6000);
    },
    remove(id) {
        const item = this.toasts.find((entry) => entry.id === id);
        if (!item) return;
        item.visible = false;
        setTimeout(() => {
            this.toasts = this.toasts.filter((entry) => entry.id !== id);
        }, 500);
    }
}" @notify.window="add($event.detail)"
    class="pointer-events-none fixed bottom-6 {{ app()->getLocale() === 'ar' ? 'right-6' : 'left-6' }} z-[999] flex flex-col gap-3"
    aria-live="polite" aria-atomic="true">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.visible" x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 translate-y-6 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-400"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 scale-95"
            class="pointer-events-auto flex w-full min-w-[280px] max-w-sm items-start gap-3 rounded-2xl border border-[#E07513]/30 bg-[#1C0705]/95 px-4 py-3.5 shadow-2xl shadow-black/50 ring-1 ring-white/5 backdrop-blur-md"
            role="alert">
            {{-- علامة النجاح --}}
            <div
                class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-emerald-500/30 bg-emerald-500/15">
                <svg class="h-5 w-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            {{-- العنوان والنص وشريط المدة --}}
            <div class="min-w-0 flex-1">
                <p class="text-sm font-black leading-snug text-white" x-text="toast.title"></p>
                <p class="mt-0.5 text-xs leading-relaxed text-stone-400" x-text="toast.message" x-show="toast.message">
                </p>
                <div class="mt-2 h-0.5 w-full overflow-hidden rounded-full bg-white/10">
                    <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-[#E07513]"
                        x-bind:style="`animation: shrink ${toast.duration ?? 6000}ms linear forwards`"></div>
                </div>
            </div>

            {{-- إغلاق الإشعار الحالي --}}
            <button @click="remove(toast.id)" type="button"
                class="mt-0.5 shrink-0 text-stone-500 transition-colors hover:text-stone-300" aria-label="Close">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </template>
</div>

{{-- حركة فراغ شريط التقدم حسب مدة الإشعار --}}
<style>
    @keyframes shrink {
        from {
            width: 100%;
        }

        to {
            width: 0%;
        }
    }
</style>
