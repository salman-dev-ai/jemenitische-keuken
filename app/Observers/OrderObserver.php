<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Notifications\NewOrderPlaced;
use App\Observers\Concerns\NotifiesAdmins;
use App\Services\OrderService;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;

class OrderObserver
{
    use NotifiesAdmins;

    /** لغة نصوص لوحة التحكم (لا تتأثر بلغة الواجهة العامة). */
    private const ADMIN_LOCALE = 'ar';

    /** يُنفَّذ بعد نجاح الـ commit فقط (آمن داخل DB::transaction). */
    public bool $afterCommit = true;

    public function created(Order $order): void
    {
        $this->notifyAdmins(new NewOrderPlaced($order));
    }

    /**
     * 📲 عند تغيير حالة الطلب إلى «مكتمل» أو «ملغى» — تنبيه المدير بزر إرسال تحديث واتساب.
     */
    public function updated(Order $order): void
    {
        if (! $this->shouldPromptStatusMessage($order)) {
            return;
        }

        try {
            $url = app(OrderService::class)->customerWhatsAppUrl($order);

            if ($url === null) {
                return;
            }

            FilamentNotification::make()
                ->title(__('messages.order_status.toast_title', [], self::ADMIN_LOCALE))
                ->body(__('messages.order_status.toast_body', ['number' => $order->order_number], self::ADMIN_LOCALE))
                ->icon('lucide-message-circle')
                ->success()
                ->persistent()
                ->actions([
                    Action::make('send_whatsapp_order_status')
                        ->label(__('messages.order_status.send_action', [], self::ADMIN_LOCALE))
                        ->url($url)
                        ->openUrlInNewTab()
                        ->button(),
                ])
                ->send();
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * هل يجب اقتراح إرسال تحديث للعميل؟ (الحالة صارت مكتملة/ملغاة لتوّها)
     */
    protected function shouldPromptStatusMessage(Order $order): bool
    {
        if (! in_array($order->status, [OrderStatus::COMPLETED, OrderStatus::CANCELLED], true)) {
            return false;
        }

        return $order->wasChanged('status');
    }
}
