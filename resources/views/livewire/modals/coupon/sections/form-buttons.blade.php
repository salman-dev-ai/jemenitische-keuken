{{--
أزرار إلغاء التسجيل وإرساله. الإلغاء يخفي النافذة فوراً عبر Alpine
مع مزامنة حالة Volt، والإرسال يعطل الزر أثناء حفظ الضيف.
--}}

<div class="flex gap-3 pt-2">
    {{-- يغلق النافذة محلياً فوراً وتتم مزامنة خاصية العرض مع الخادم. --}}
    <button
        type="button"
        x-on:click="open = false"
        class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 font-medium text-gray-700 transition hover:bg-gray-50"
    >
        {{ __('offers.modal.cancel') }}
    </button>

    {{-- يرسل النموذج إلى Volt ويعرض مؤشر الانتظار حتى اكتمال الحفظ. --}}
    <button
        type="submit"
        wire:loading.attr="disabled"
        wire:target="submitGuest"
        class="flex-1 rounded-lg bg-amber-800 px-4 py-2.5 font-medium text-white transition hover:bg-amber-900 disabled:cursor-wait disabled:opacity-70"
    >
        <span wire:loading.remove wire:target="submitGuest">
            {{ __('offers.modal.submit') }}
        </span>
        <span
            wire:loading
            wire:target="submitGuest"
            class="inline-block h-4 w-4 border-2 border-white/30 border-t-white rounded-full animate-spin align-middle"
        ></span>
    </button>
</div>
