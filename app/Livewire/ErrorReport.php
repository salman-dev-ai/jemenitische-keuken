<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ErrorReport extends Component
{
    #[Locked]
    public int $status = 500;

    #[Locked]
    public string $url = '';

    #[Validate('nullable|email|max:120')]
    public string $email = '';

    #[Validate('nullable|string|max:1000')]
    public string $message = '';

    public bool $sent = false;

    public function mount(int $status = 500): void
    {
        $this->status = $status;
        $this->url = Str::limit(request()->fullUrl(), 500, '');
    }

    public function send(): void
    {
        $key = 'error-report:' . request()->ip();

        // max. 3 meldingen per 10 minuten per IP
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $this->addError('message', 'Te veel meldingen. Probeer het later opnieuw.');
            return;
        }

        $data = $this->validate();
        RateLimiter::hit($key, 600);

        Log::warning('Foutmelding van bezoeker', $data + [
            'status' => $this->status,
            'url'    => $this->url,
            'ip'     => request()->ip(),
            'agent'  => Str::limit((string) request()->userAgent(), 200, ''),
        ]);

        $this->sent = true;
    }

    public function render()
    {
        return view('livewire.error-report');
    }
}
