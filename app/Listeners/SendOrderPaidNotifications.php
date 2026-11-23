<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Notifications\NewOrderPlaced;
use App\Observers\Concerns\NotifiesAdmins;

class SendOrderPaidNotifications
{
    use NotifiesAdmins;

    public function handle(OrderPaid $event): void
    {
        $this->notifyAdmins(new NewOrderPlaced($event->order));
    }
}
