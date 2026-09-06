<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Reservation;
use App\Models\User;
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
            )
                ->description('جميع الطلبات')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->icon('heroicon-o-shopping-bag')
                ->color('primary'),

            Stat::make(
                'إجمالي الحجوزات',
                Reservation::count()
            )
                ->description('جميع الحجوزات')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->icon('heroicon-o-calendar-days')
                ->color('info'),

            Stat::make(
                'إجمالي المستخدمين',
                User::count()
            )
                ->description('المستخدمون المسجلون')
                ->descriptionIcon('heroicon-m-users')
                ->icon('heroicon-o-users')
                ->color('success'),

            Stat::make(
                'إجمالي الإيرادات',
                number_format(
                    Order::sum('total'),
                    2
                ) . ' €'
            )
                ->description('إجمالي قيمة الطلبات')
                ->descriptionIcon('heroicon-m-banknotes')
                ->icon('heroicon-o-banknotes')
                ->color('warning'),
        ];
    }
}
