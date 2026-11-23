<?php

declare(strict_types=1);

namespace App\Services\Payments;

use App\Enums\PaymentAttemptStatus;
use App\Enums\PaymentStatus;
use App\Events\OrderPaid;
use App\Exceptions\PaymentException;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\RestaurantSetting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PayPro\Client;
use PayPro\Exception\ApiErrorException;

class PayProService
{
    // ───────────── الاتصال ─────────────

    /** عميل PayPro بمفتاح المطعم (أو مفتاح مُمرَّر عند الاختبار قبل الحفظ). */
    public function client(?string $apiKey = null): Client
    {
        $apiKey ??= RestaurantSetting::current()->paypro_api_key;

        if (blank($apiKey)) {
            throw new PaymentException('الدفع الإلكتروني غير مضبوط لهذا المطعم.');
        }

        $apiUrl = config('services.paypro.api_url');

        return $apiUrl
            ? new Client(['api_url' => $apiUrl, 'api_key' => $apiKey])
            : new Client($apiKey);
    }

    /**
     * يتحقق من المفتاح، ينشئ Webhook للمطعم، ويحفظ الإعدادات مشفّرة.
     * يُستدعى من صفحة إعدادات الدفع.
     */
    public function connect(string $apiKey): RestaurantSetting
    {
        $webhookUrl = route('webhooks.paypro');

        if (! str_starts_with($webhookUrl, 'https://')) {
            throw new PaymentException('PayPro يشترط رابط Webhook عبر HTTPS. اضبط APP_URL على رابط https عام.');
        }

        $client = $this->client($apiKey);

        try {
            $client->payments->list(['limit' => 1]); // اختبار المفتاح
        } catch (ApiErrorException $e) {
            report($e);
            throw new PaymentException('مفتاح PayPro غير صحيح أو لا يملك الصلاحية.');
        }

        $settings = RestaurantSetting::current();
        $this->deleteWebhookQuietly($settings);

        try {
            $webhook = $client->webhooks->create([
                'name' => 'Restaurant orders',
                'description' => 'Order payment notifications',
                'url' => $webhookUrl,
                'active' => true,
                'enabled_event_types' => ['all'],
            ]);
        } catch (ApiErrorException $e) {
            report($e);
            throw new PaymentException('تعذّر إنشاء Webhook في حساب PayPro.');
        }

        $settings->update([
            'paypro_api_key' => $apiKey,
            'paypro_webhook_id' => $webhook->id,
            'paypro_webhook_secret' => $webhook->secret,
            'paypro_connected_at' => now(),
        ]);

        return $settings;
    }

    public function disconnect(): void
    {
        $settings = RestaurantSetting::current();
        $this->deleteWebhookQuietly($settings);

        $settings->update([
            'online_payments_enabled' => false,
            'paypro_api_key' => null,
            'paypro_webhook_id' => null,
            'paypro_webhook_secret' => null,
            'paypro_connected_at' => null,
        ]);
    }

    private function deleteWebhookQuietly(RestaurantSetting $settings): void
    {
        if (blank($settings->paypro_webhook_id) || blank($settings->paypro_api_key)) {
            return;
        }

        try {
            $this->client()->webhooks->get($settings->paypro_webhook_id)->delete();
        } catch (\Throwable $e) {
            Log::warning('PayPro: failed to delete old webhook', ['message' => $e->getMessage()]);
        }
    }

    // ───────────── إنشاء الدفعة ─────────────

    /**
     * ينشئ دفعة لطلب ويُرجع سجل Payment فيه checkout_url لتحويل الزبون إليه.
     * المبلغ يُؤخذ من الطلب في الخادم فقط (لا يثق بالعميل أبداً).
     */
    public function createPayment(Order $order, string $returnUrl, ?string $cancelUrl = null): Payment
    {
        // إعادة استخدام رابط حديث لنفس الطلب (يمنع دفعات مكررة عند الضغط مرتين)
        $existing = $order->payments()
            ->where('status', PaymentAttemptStatus::PENDING)
            ->whereNotNull('checkout_url')
            ->where('created_at', '>=', now()->subMinutes(15))
            ->latest('id')
            ->first();

        if ($existing) {
            return $existing;
        }

        $amount = (int) round(((float) $order->total) * 100); // بالسنت (تأكد منه في الاختبار الأول)

        if ($amount < 1) {
            throw new PaymentException('مبلغ الطلب غير صالح للدفع.');
        }

        $body = [
            'amount' => $amount,
            'currency' => 'EUR',
            'description' => 'Order '.$order->order_number,
            'return_url' => $returnUrl,
            'locale' => in_array($order->locale, ['nl', 'en', 'de', 'fr'], true) ? $order->locale : 'en',
            'metadata' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ],
        ];

        if ($cancelUrl) {
            $body['cancel_url'] = $cancelUrl;
        }

        try {
            $remote = $this->client()->payments->create($body);
        } catch (ApiErrorException $e) {
            report($e);
            throw new PaymentException('تعذّر إنشاء عملية الدفع. حاول مرة أخرى.');
        }

        $checkoutUrl = $this->extractCheckoutUrl($remote);

        if ($checkoutUrl === null) {
            Log::error('PayPro: payment created without checkout link', ['payment_id' => $remote->id ?? null]);
            throw new PaymentException('تعذّر الحصول على رابط الدفع.');
        }

        $order->update([
            'payment_status' => PaymentStatus::PENDING->value,
            'payment_method' => 'online',
        ]);

        return $order->payments()->create([
            'provider' => 'paypro',
            'provider_payment_id' => $remote->id,
            'amount' => $amount,
            'currency' => 'EUR',
            'status' => PaymentAttemptStatus::PENDING,
            'provider_state' => $remote->state ?? null,
            'checkout_url' => $checkoutUrl,
        ]);
    }

    private function extractCheckoutUrl(object $remote): ?string
    {
        foreach (['links', '_links'] as $key) {
            $links = $remote->{$key} ?? null;

            if (is_array($links) && ! empty($links['checkout'])) {
                return (string) $links['checkout'];
            }
        }

        return null;
    }

    // ───────────── مزامنة الحالة (قلب النظام) ─────────────

    /**
     * يجلب الدفعة من PayPro API (لا يثق بمحتوى الـ Webhook) ويحدّث الدفعة والطلب.
     * آمن للاستدعاء المتكرر (idempotent) ومن الـ Webhook ومن صفحة العودة معاً.
     */
    public function syncPayment(string $providerPaymentId): ?Payment
    {
        $local = Payment::query()->where('provider_payment_id', $providerPaymentId)->first();

        if ($local === null) {
            Log::warning('PayPro: payment not found locally', ['provider_payment_id' => $providerPaymentId]);

            return null;
        }

        try {
            $remote = $this->client()->payments->get($providerPaymentId);
        } catch (ApiErrorException $e) {
            report($e);
            throw new PaymentException('تعذّر جلب حالة الدفعة من PayPro.');
        }

        return DB::transaction(function () use ($local, $remote): Payment {
            /** @var Payment $payment */
            $payment = Payment::query()->whereKey($local->id)->lockForUpdate()->firstOrFail();
            /** @var Order $order */
            $order = Order::query()->whereKey($payment->order_id)->lockForUpdate()->firstOrFail();

            $newStatus = PaymentAttemptStatus::fromProviderState($remote->state ?? null);
            $wasPaid = $payment->status === PaymentAttemptStatus::PAID;

            // الأحداث قد تصل بترتيب خاطئ: لا نُنزل دفعة مدفوعة إلى حالة أقل أبداً
            if ($wasPaid) {
                $newStatus = PaymentAttemptStatus::PAID;
            }

            // حماية: المبلغ لدى PayPro يجب أن يطابق ما حسبناه نحن
            if ($newStatus === PaymentAttemptStatus::PAID && ! $wasPaid
                && (int) ($remote->amount ?? 0) !== $payment->amount) {
                Log::critical('PayPro: amount mismatch', [
                    'payment_id' => $payment->id,
                    'expected' => $payment->amount,
                    'received' => $remote->amount ?? null,
                ]);
                $payment->update(['failure_reason' => 'amount_mismatch']);

                return $payment;
            }

            $payMethod = data_get($remote, 'pay_method.id');

            $payment->fill([
                'status' => $newStatus,
                'provider_state' => $remote->state ?? $payment->provider_state,
                'pay_method' => $payMethod ?: $payment->pay_method,
                'refunded_amount' => (int) ($remote->refunded_amount ?? $payment->refunded_amount),
                'failure_reason' => $remote->fail_reason ?? $payment->failure_reason,
            ]);

            if ($newStatus === PaymentAttemptStatus::PAID && ! $wasPaid) {
                $payment->paid_at = ! empty($remote->paid_at) ? Carbon::parse($remote->paid_at) : now();
            }

            $payment->save();

            // ── تحديث الطلب ──
            if ($newStatus === PaymentAttemptStatus::PAID) {
                $fullyRefunded = $payment->refunded_amount >= $payment->amount;

                $order->update([
                    'payment_status' => ($fullyRefunded ? PaymentStatus::REFUNDED : PaymentStatus::PAID)->value,
                    'payment_method' => $payment->pay_method ?: 'online',
                ]);

                if (! $wasPaid) {
                    DB::afterCommit(fn () => OrderPaid::dispatch($order->fresh()));
                }
            } elseif (in_array($newStatus, [PaymentAttemptStatus::FAILED, PaymentAttemptStatus::CANCELED], true)
                && $order->payment_status !== PaymentStatus::PAID->value) {
                $order->update(['payment_status' => PaymentStatus::FAILED->value]);
            }

            return $payment;
        });
    }

    // ───────────── الاسترداد ─────────────

    /**
     * استرداد كلي أو جزئي. المبلغ بالسنت؛ null = كل المتاح.
     *
     * @param  array<string, string>  $extra  مثل iban و account_holder_name (مطلوبة لـ bancontact/creditcard)
     */
    public function refund(
        Payment $payment,
        ?int $amountCents = null,
        ?string $reason = null,
        ?string $description = null,
        ?int $userId = null,
        array $extra = [],
    ): Refund {
        return DB::transaction(function () use ($payment, $amountCents, $reason, $description, $userId, $extra): Refund {
            $payment = Payment::query()->whereKey($payment->id)->lockForUpdate()->firstOrFail();

            if ($payment->status !== PaymentAttemptStatus::PAID) {
                throw new PaymentException('لا يمكن استرداد دفعة غير مدفوعة.');
            }

            $amount = $amountCents ?? $payment->refundableAmount();

            if ($amount < 1 || $amount > $payment->refundableAmount()) {
                throw new PaymentException('مبلغ الاسترداد غير صالح أو يتجاوز المبلغ المتاح.');
            }

            try {
                $remotePayment = $this->client()->payments->get($payment->provider_payment_id);
                $remoteRefund = $remotePayment->refund(array_filter([
                    'amount' => $amount,
                    'description' => $description,
                    'reason' => $reason,
                ] + $extra));
            } catch (ApiErrorException $e) {
                report($e);
                throw new PaymentException('رفض PayPro طلب الاسترداد (تحقق من رصيد الحساب أو بيانات IBAN).');
            }

            $refund = $payment->refunds()->create([
                'provider_refund_id' => $remoteRefund->id ?? null,
                'amount' => $amount,
                'status' => $remoteRefund->state ?? 'open',
                'reason' => $reason,
                'description' => $description,
                'created_by' => $userId,
            ]);

            $payment->increment('refunded_amount', $amount);

            if ($payment->refundableAmount() === 0) {
                $payment->order()->update(['payment_status' => PaymentStatus::REFUNDED->value]);
            }

            return $refund;
        });
    }
}
