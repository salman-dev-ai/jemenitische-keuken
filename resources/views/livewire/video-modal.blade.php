<?php

use function Livewire\Volt\{state, on};

state([
    'show' => false,
    'url' => null,
    'title' => '',
]);

on(['open-video-modal' => function (string $url, string $title = '') {
    $this->url = $url;
    $this->title = $title;
    $this->show = true;
}]);

$close = function () {
    $this->show = false;
    $this->url = null;
    $this->title = '';
};

?>

<div>
    @if ($show && $url)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4"
            wire:click.self="close"
            x-data
            @keydown.escape.window="$wire.close()"
        >
            <div class="relative w-full max-w-4xl">

                <button
                    type="button"
                    wire:click="close"
                    class="absolute -top-12 end-0 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white backdrop-blur-sm transition hover:bg-white/20"
                    aria-label="{{ __('offers.video_modal.close') }}"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                @if ($title)
                    <h3 class="mb-3 text-center text-lg font-bold text-white">
                        {{ $title }}
                    </h3>
                @endif

                <div class="overflow-hidden rounded-2xl bg-black shadow-2xl">
                    <video
                        src="{{ $url }}"
                        controls
                        autoplay
                        playsinline
                        class="h-auto max-h-[80vh] w-full"
                    >
                        {{ __('offers.video_modal.not_supported') }}
                    </video>
                </div>

            </div>
        </div>
    @endif
</div>