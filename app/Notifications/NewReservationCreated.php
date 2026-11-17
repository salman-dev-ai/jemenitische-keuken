<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Filament\Resources\Reservations\ReservationResource;
use App\Models\Reservation;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Notifications\Notification;

/**
 * إشعار لوحة التحكم عند وصول حجز جديد (غير مُصفَّف — فوري).
 */
class NewReservationCreated extends Notification
{
    private const ADMIN_LOCALE = 'ar';

    public function __construct(
        public readonly Reservation $reservation,
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
                ->title(__('admin_notifications.reservation.title', [
                    'code' => $this->reservation->reference_code,
                ]))
                ->icon('lucide-clipboard-list')
                ->iconColor('info')
                ->body(__('admin_notifications.reservation.body', [
                    'name' => $this->reservation->customer_name,
                    'guests' => $this->reservation->party_size,
                    'date' => $this->reservation->reservation_date->format('Y-m-d'),
                    'time' => $this->reservation->reservation_time,
                ]))
                ->actions([
                    Action::make('view')
                        ->label(__('admin_notifications.reservation.view'))
                        ->url(ReservationResource::getUrl('edit', ['record' => $this->reservation]))
                        ->markAsRead(),
                ])
                ->getDatabaseMessage();
        } finally {
            app()->setLocale($previousLocale);
        }
    }
}
