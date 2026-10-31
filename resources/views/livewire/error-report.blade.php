{{-- Valt binnen de Alpine-scope van errors/layout (lang, ui) --}}
<div>
    @if ($sent)
        <p class="rp-ok" role="status" x-text="ui[lang].thanks">Bedankt, we kijken ernaar.</p>
    @else
        <form wire:submit="send" novalidate>
            <label>
                <span x-text="ui[lang].email">E-mailadres (optioneel)</span>
                <input type="email" wire:model="email" autocomplete="email" inputmode="email" dir="ltr">
            </label>
            @error('email') <p class="rp-err">{{ $message }}</p> @enderror

            <label>
                <span x-text="ui[lang].message">Wat probeerde je te doen?</span>
                <textarea wire:model="message" rows="3"></textarea>
            </label>
            @error('message') <p class="rp-err">{{ $message }}</p> @enderror

            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="send">
                <span x-text="ui[lang].send">Verstuur melding</span>
            </button>
        </form>
    @endif
</div>
