<?php

namespace App\Livewire;

use App\Models\ContactMessage;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ContactForm extends Component
{
    #[Validate('required|string|min:3|max:100')]
    public string $name = '';

    #[Validate('required|email|max:150')]
    public string $email = '';

    #[Validate('nullable|string|max:30')]
    public string $phone = '';

    #[Validate('required|string')]
    public string $subject = 'inquiry';

    #[Validate('required|string|min:10|max:2000')]
    public string $message = '';

    public function submitMessage(): void
    {
        // ✅ 1. التحقق من القيود
        $validated = $this->validate();

        // ✅ 2. الحفظ الفعلي في قاعدة البيانات
        ContactMessage::create($validated);

        // ✅ 3. إشعار نجاح
        session()->flash(
            'contact_success',
            __('messages.contact.successMsg')
                ?? 'شكراً لتواصلك معنا! استلمنا رسالتك وسيتواصل معك فريق الضيافة خلال 24 ساعة.'
        );

        // ✅ 4. إعادة تهيئة الحقول
        $this->reset(['name', 'email', 'phone', 'subject', 'message']);
        $this->subject = 'inquiry';
    }

    public function render()
    {
        return view('livewire.contact-section');
    }
}
