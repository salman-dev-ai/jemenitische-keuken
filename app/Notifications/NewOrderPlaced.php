<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Notifications\Notification;

/**
 * إشعار لوحة التحكم عند وصول طلب جديد.
 *
 * لا يُنفّذ ShouldQueue عن قصد: يُكتب مباشرة في قاعدة البيانات
 * فيظهر في الجرس دون الحاجة إلى Queue Worker.
 */
class NewOrderPlaced extends Notification
{
    /** لغة المدير (لا تتأثر بلغة الزبون الذي أنشأ الطلب). */
    private const ADMIN_LOCALE = 'ar';

    public function __construct(
        public readonly Order $order,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $previousLocale = app()->getLocale();

        app()->setLocale(self::ADMIN_LOCALE);

        try {
            return FilamentNotification::make()
                ->title(__('admin_notifications.order.title', [
                    'number' => $this->order->order_number,
                ]))
                ->icon('lucide-shopping-cart')
                ->iconColor('warning')
                ->body(__('admin_notifications.order.body', [
                    'name' => $this->order->customer_name,
                    'type' => $this->order->type->getLabel(),
                    'total' => number_format((float) $this->order->total, 2),
                ]))
                ->actions([
                    Action::make('view')
                        ->label(__('admin_notifications.order.view'))
                        ->url(OrderResource::getUrl('edit', ['record' => $this->order]))
                        ->markAsRead(),
                ])
                ->getDatabaseMessage();
        } finally {
            app()->setLocale($previousLocale);
        }
    }
}
