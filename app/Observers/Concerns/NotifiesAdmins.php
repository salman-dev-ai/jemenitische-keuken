<?php

declare(strict_types=1);

namespace App\Observers\Concerns;

use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as NotificationFacade;

trait NotifiesAdmins
{
    /** الأدوار التي تستلم التنبيهات. */
    private const NOTIFIED_ROLES = ['super_admin', 'admin'];

    /**
     * يرسل الإشعار لمديري اللوحة، ولا يُفشل العملية الأصلية إن حدث خطأ.
     * يتجاهل الإنشاء اليدوي من مدير مسجّل دخوله.
     */
    protected function notifyAdmins(Notification $notification): void
    {
        try {
            if (auth()->user()?->hasAnyRole(self::NOTIFIED_ROLES)) {
                return;
            }

            $admins = User::query()->role(self::NOTIFIED_ROLES)->get();

            if ($admins->isNotEmpty()) {
                NotificationFacade::send($admins, $notification);
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
