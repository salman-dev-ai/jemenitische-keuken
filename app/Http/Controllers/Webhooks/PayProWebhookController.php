<?php

declare(strict_types=1);

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\RestaurantSetting;
use App\Models\WebhookEvent;
use App\Services\Payments\PayProService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PayPro\Entities\Webhook;
use PayPro\Exception\InvalidEventPayloadException;
use PayPro\Exception\SignatureVerificationException;

class PayProWebhookController extends Controller
{
    public function __invoke(Request $request, PayProService $payments): Response
    {
        $secret = RestaurantSetting::current()->paypro_webhook_secret;

        if (blank($secret)) {
            return response('', 503);
        }

        // 1) التحقق من التوقيع + نافذة 10 دقائق (على النص الخام كما وصل)
        try {
            $event = Webhook::createEvent(
                $request->getContent(),
                (string) $request->header('PayPro-Signature'),
                $secret,
                (int) $request->header('PayPro-Timestamp'),
            );
        } catch (SignatureVerificationException|InvalidEventPayloadException) {
            return response('', 400);
        }

        // 2) منع التكرار: كل حدث له ID فريد
        $record = WebhookEvent::query()->firstOrCreate(
            ['provider' => 'paypro', 'event_id' => (string) $event->id],
            ['event_type' => (string) $event->event_type],
        );

        if ($record->processed_at !== null) {
            return response('', 200);
        }

        // 3) المعالجة: أي حدث يخص دفعة ⇒ نجلب حالتها من API (لا نثق بالحمولة)
        // أي خطأ يُرمى ⇒ 500 فيعيد PayPro المحاولة لاحقاً (حتى 3 أيام)
        if (str_starts_with((string) $event->event_type, 'payment.')) {
            $paymentId = data_get($event->payload, 'id');

            if (is_string($paymentId) && $paymentId !== '') {
                $payments->syncPayment($paymentId);
            }
        }

        $record->update(['processed_at' => now()]);

        return response('', 200);
    }
}
