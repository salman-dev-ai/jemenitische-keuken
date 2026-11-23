<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PaymentAttemptStatus;
use App\Models\Order;
use App\Services\Payments\PayProService;
use Illuminate\Contracts\View\View;

class PaymentReturnController extends Controller
{
    public function __invoke(string $locale, Order $order, PayProService $payments): View
    {
        $payment = $order->payments()->latest('id')->first();

        // الـ Webhook قد لا يكون وصل بعد: نسأل PayPro مباشرة (لا نثق بعودة الزبون وحدها)
        if ($payment && $payment->status === PaymentAttemptStatus::PENDING) {
            try {
                $payments->syncPayment($payment->provider_payment_id);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $order->refresh();

        $state = match ($order->payment_status) {
            'paid', 'refunded' => 'paid',
            'failed' => 'failed',
            default => 'pending',
        };

        return view('payment.return', ['order' => $order, 'state' => $state]);
    }
}
