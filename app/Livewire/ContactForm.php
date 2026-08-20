<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;

class ContactForm extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $subject = 'inquiry';
    public string $message = '';

    protected $rules = [
        'name' => 'required|min:3|max:100',
        'email' => 'required|email|max:150',
        'phone' => 'nullable|string|max:30',
        'subject' => 'required|string',
        'message' => 'required|min:10|max:2000',
    ];

    public function submitMessage(): void
    {
        $validatedData = $this->validate();

        // إشعار فوري لخدمة العملاء
        session()->flash('contact_success', __('messages.contact.successMsg') ?? 'شكراً لتواصلك معنا! استلمنا رسالتك وسيتواصل معك فريق الضيافة في أقرب وقت.');

        $this->reset(['name', 'email', 'phone', 'message']);
    }

    public function render()
    {
        return view('livewire.contact-section');
    }
}
