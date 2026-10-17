{{--
    نافذة تأكيد أنيقة داخل الموقع.
    بديل احترافي عن نافذة المتصفح الافتراضية (wire:confirm)، بنفس هوية إشعارات الموقع.

    الاستخدام من أي زر:
        window.dispatchEvent(new CustomEvent('confirm-action', { detail: {
            title: '...', message: '...', confirmLabel: '...', cancelLabel: '...',
            onConfirm: () => $wire.someMethod(),
        } }))
--}}
<div x-data="{
    open: false,
    title: '',
    message: '',
    confirmLabel: @js(__('messages.confirm.yes')),
    cancelLabel: @js(__('messages.confirm.cancel')),
    action: null,
    show(detail) {
        this.title = detail.title ?? '';
        this.message = detail.message ?? '';
        this.confirmLabel = detail.confirmLabel ?? @js(__('messages.confirm.yes'));
        this.cancelLabel = detail.cancelLabel ?? @js(__('messages.confirm.cancel'));
        this.action = detail.onConfirm ?? null;
        this.open = true;
    },
    close() {
        this.open = false;
        this.action = null;
    },
    confirm() {
        const run = this.action;
        this.close();
        if (typeof run === 'function') run();
    }
}"
    @confirm-action.window="show($event.detail)"
    @keydown.escape.window="open && close()"
    x-show="open" x-cloak x-transition.opacity.duration.200ms
    class="fixed inset-0 z-[1000] flex items-center justify-center px-4" role="dialog" aria-modal="true">
    {{-- الخلفية المعتمة --}}
    <div @click="close()" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

    {{-- بطاقة التأكيد --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="relative w-full max-w-sm overflow-hidden rounded-3xl border border-[#E07513]/30 bg-[#1C0705] p-6 text-center shadow-2xl shadow-black/60 ring-1 ring-white/5">

        {{-- أيقونة التنبيه --}}
        <div
            class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl border border-amber-500/30 bg-amber-500/15">
            <svg class="h-7 w-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
        </div>

        {{-- العنوان والرسالة --}}
        <h3 class="text-lg font-black text-white" x-text="title"></h3>
        <p class="mt-2 text-sm leading-relaxed text-stone-400" x-text="message"></p>

        {{-- الأزرار --}}
        <div class="mt-6 flex gap-3">
            <button type="button" @click="close()" x-text="cancelLabel"
                class="flex-1 rounded-xl border border-white/10 bg-white/5 py-3 text-sm font-bold text-stone-300 transition hover:bg-white/10"></button>
            <button type="button" @click="confirm()" x-text="confirmLabel"
                class="flex-1 rounded-xl bg-gradient-to-r from-[#E07513] to-amber-600 py-3 text-sm font-black text-white shadow-lg shadow-[#E07513]/20 transition hover:brightness-110"></button>
        </div>
    </div>
</div>
