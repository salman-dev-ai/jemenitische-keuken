<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Notifications\NewReservationCreated;
use App\Observers\Concerns\NotifiesAdmins;
use App\Services\ReservationService;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;

class ReservationObserver
{
    use NotifiesAdmins;

    /** لغة نصوص لوحة التحكم (لا تتأثر بلغة الواجهة العامة). */
    private const ADMIN_LOCALE = 'ar';

    public bool $afterCommit = true;

    public function created(Reservation $reservation): void
    {
        $this->notifyAdmins(new NewReservationCreated($reservation));
    }

    /**
     * 📲 عند تأكيد الحجز — أو تعديل موعده بعد التأكيد —
     * نعرض للمدير زر إرسال رسالة التأكيد للعميل على واتساب.
     */
    public function updated(Reservation $reservation): void
    {
        if (! $this->shouldPromptConfirmation($reservation)) {
            return;
        }

        try {
            $url = app(ReservationService::class)->customerWhatsAppUrl($reservation);

            if ($url === null) {
                return;
            }

            FilamentNotification::make()
                ->title(__('messages.reservation_confirmation.toast_title', [], self::ADMIN_LOCALE))
                ->body(__('messages.reservation_confirmation.toast_body', [
                    'name' => $reservation->customer_name,
                ], self::ADMIN_LOCALE))
                ->icon('lucide-message-circle')
                ->success()
                ->persistent()
                ->actions([
                    Action::make('send_whatsapp_confirmation')
                        ->label(__('messages.reservation_confirmation.send_action', [], self::ADMIN_LOCALE))
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
     * هل يستحق هذا التحديث تذكير المدير بإرسال تأكيد للعميل؟
     */
    protected function shouldPromptConfirmation(Reservation $reservation): bool
    {
        if ($reservation->status !== ReservationStatus::CONFIRMED) {
            return false;
        }

        return $reservation->wasChanged('status')
            || $reservation->wasChanged('reservation_date')
            || $reservation->wasChanged('reservation_time');
    }
}
