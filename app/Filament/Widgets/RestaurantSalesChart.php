<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RestaurantSalesChart extends ChartWidget
{
    protected ?string $heading = 'المبيعات خلال الأسبوع';

    protected function getData(): array
    {
        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);

            $labels[] = $date->translatedFormat('D');

            $data[] = Order::query()
                ->whereDate('created_at', $date)
                ->where('status', '!=', 'cancelled')
                ->sum('total');
        }



        return [
            'datasets' => [
                [
                    'label' => 'المبيعات',
                    'data' => $data,
                ],
            ],

            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
