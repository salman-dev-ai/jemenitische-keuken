<?php

namespace App\Livewire;

use App\Models\RestaurantSetting;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ContactForm extends Component
{
    #[Validate('required|string|min:3|max:100')]
    public string $name = '';

    #[Validate('nullable|email|max:150')]
    public string $email = '';

    #[Validate('nullable|string|max:30')]
    public string $phone = '';

    #[Validate('required|string|in:inquiry,event,catering')]
    public string $subject = 'inquiry';

    #[Validate('required|string|min:10|max:2000')]
    public string $message = '';

    /**
     * يتحقق ثم يبني رابط واتساب جاهزًا. لا يُرسل الموقع الرسالة بنفسه؛
     * يفتح العميل واتساب ويضغط الإرسال. يعيد null إذا كان الرقم غير صالح.
     */
    public function submit(): ?string
    {
        $validated = $this->validate();

        $url = $this->buildWhatsAppUrl($validated);

        if ($url === null) {
            $this->addError('general', __('messages.contact.form.whatsapp_unavailable'));

            return null;
        }

        $this->reset(['name', 'email', 'phone', 'subject', 'message']);
        $this->subject = 'inquiry';

        return $url;
    }

    /** رقم واتساب المطعم من الإعدادات بصيغة دولية (أرقام فقط)، أو null إذا غير صالح. */
    private function whatsAppNumber(): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) RestaurantSetting::current()->whatsapp);

        // صيغة دولية: 8–15 رقمًا ولا يبدأ بصفر.
        return preg_match('/^[1-9]\d{7,14}$/', $digits) === 1 ? $digits : null;
    }

    /** بناء رسالة واتساب تتضمن بيانات العميل ورسالته. */
    private function buildWhatsAppUrl(array $v): ?string
    {
        $number = $this->whatsAppNumber();

        if ($number === null) {
            return null;
        }

        $lines = ['*رسالة جديدة من نموذج التواصل*', "الاسم: {$v['name']}"];

        if (! empty($v['email'])) {
            $lines[] = "البريد: {$v['email']}";
        }
        if (! empty($v['phone'])) {
            $lines[] = "الهاتف: {$v['phone']}";
        }

        $lines[] = 'نوع الاستفسار: '.__('messages.contact.form.subjects.'.$v['subject']);
        $lines[] = '';
        $lines[] = $v['message'];

        return 'https://wa.me/'.$number.'?text='.rawurlencode(implode("\n", $lines));
    }

    public function render()
    {
        return view('livewire.contact-section', [
            'whatsappAvailable' => $this->whatsAppNumber() !== null,
        ]);
    }
}
