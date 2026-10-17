<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\RestaurantSetting;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RestaurantStatus extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $settings = RestaurantSetting::query()->first();
        $totalRevenue = Order::query()->where('status', '!=', 'cancelled')->sum('total');

        return [
            Stat::make(
                'الطلبات الإلكترونية',
                $settings?->accepts_online_orders ? 'مفعلة' : 'متوقفة'
            )
                ->description(
                    $settings?->accepts_online_orders
                        ? 'المطعم يستقبل الطلبات'
                        : 'المطعم لا يستقبل الطلبات'
                )
                ->descriptionIcon(
                    $settings?->accepts_online_orders
                        ? 'heroicon-m-check-circle'
                        : 'heroicon-m-x-circle'
                )
                ->color(
                    $settings?->accepts_online_orders
                        ? 'success'
                        : 'danger'
                ),

            Stat::make(
                'الحجوزات',
                $settings?->accepts_reservations ? 'مفعلة' : 'متوقفة'
            )
                ->description(
                    $settings?->accepts_reservations
                        ? 'المطعم يستقبل الحجوزات'
                        : 'الحجوزات متوقفة'
                )
                ->descriptionIcon(
                    $settings?->accepts_reservations
                        ? 'heroicon-m-check-circle'
                        : 'heroicon-m-x-circle'
                )
                ->color(
                    $settings?->accepts_reservations
                        ? 'success'
                        : 'danger'
                ),

            // إجمالي الإيرادات
            Stat::make('إجمالي الإيرادات', number_format($totalRevenue, 2).' €')
                ->description('إيرادات الطلبات المعتمدة')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([5, 8, 10, 15, 12, 20, 25])
                ->icon('heroicon-o-banknotes')
                ->color('warning'),
        ];
    }
}
