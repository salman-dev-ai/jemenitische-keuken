<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Reservation;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RestaurantStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(
                'إجمالي الطلبات',
                Order::count() 
            ),

            Stat::make(
                'إجمالي الحجوزات',
                Reservation::count()
            ),
        ];
    }
}
