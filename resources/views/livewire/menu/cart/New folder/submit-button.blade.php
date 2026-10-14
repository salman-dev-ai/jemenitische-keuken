{{-- زر الإرسال --}}
<button type="submit" wire:loading.attr="disabled" wire:target="checkout"
    class="w-full py-4 bg-gradient-to-r from-[#E07513] to-[#B85709] hover:from-[#c2620a] hover:to-[#994303] text-white font-black rounded-xl text-sm shadow-lg shadow-amber-500/20 transition-all flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
    <span wire:loading.remove wire:target="checkout" class="inline-flex items-center gap-2">
        {{ __('messages.reservation.confirmReservation') }}
        <x-lucide-crown class="w-5 h-5" aria-hidden="true" />
    </span>
    <span wire:loading wire:target="checkout" class="inline-flex items-center gap-2">
        <span class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
        {{ __('messages.reservation.processingReservation') }}
    </span>
</button>

<p class="text-[10px] text-center text-stone-400 flex items-center justify-center gap-1">
    <x-lucide-shield-check class="w-3 h-3" aria-hidden="true" />
    <span>{{ __('messages.reservation.smsNotice') }}</span>
</p>
