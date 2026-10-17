<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrderTypesChart extends ChartWidget
{
    protected ?string $heading = 'أنواع الطلبات';

    protected static ?int $sort = 2;

    protected ?string $maxHeight = '360px';

    protected ?string $pollingInterval = null;

    protected int|string|array $columnSpan = [
        'default' => 1,
        'lg' => 1,
    ];

    /**
     * إجمالي الطلبات (غير الملغاة) يظهر تحت العنوان.
     */
    public function getDescription(): ?string
    {
        $total = Order::query()
            ->where('status', '!=', 'cancelled')
            ->whereIn('type', ['pickup', 'dine_in', 'delivery'])
            ->count();

        return 'إجمالي الطلبات: '.number_format($total);
    }

    protected function getData(): array
    {
        $orderCounts = Order::query()
            ->select('type', DB::raw('count(*) as total'))
            ->where('status', '!=', 'cancelled')
            ->whereIn('type', ['pickup', 'dine_in', 'delivery'])
            ->groupBy('type')
            ->pluck('total', 'type');

        return [
            'datasets' => [
                [
                    'label' => 'عدد الطلبات',
                    'data' => [
                        (int) $orderCounts->get('pickup', 0),
                        (int) $orderCounts->get('dine_in', 0),
                        (int) $orderCounts->get('delivery', 0),
                    ],

                    // ألوان مستوحاة من شعار المطبخ اليمني
                    'backgroundColor' => [
                        '#C8650A', // برتقالي محروق — استلام
                        '#7A1E1E', // عنابي دافئ — داخل المطعم
                        '#E8B04B', // ذهبي رملي — توصيل
                    ],
                    'hoverBackgroundColor' => [
                        '#E07A12',
                        '#942626',
                        '#F2C269',
                    ],

                    'borderWidth' => 0,
                    'borderRadius' => 12,
                    'borderSkipped' => false,
                    'maxBarThickness' => 56,
                ],
            ],
            'labels' => [
                'استلام',
                'داخل المطعم',
                'توصيل إلى المنزل',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array|RawJs
    {
        return RawJs::make(<<<'JS'
        {
            responsive: true,
            maintainAspectRatio: true,
            animation: {
                duration: 1000,
                easing: 'easeOutQuart',
            },
            layout: { padding: 8 },
            scales: {
                x: {
                    reverse: true,
                    grid: { display: false },
                    border: { display: false },
                    ticks: {
                        font: {
                            family: 'Tajawal, Cairo, sans-serif',
                            size: 13,
                            weight: '600',
                        },
                    },
                },
                y: {
                    position: 'right',
                    beginAtZero: true,
                    border: { display: false },
                    grid: {
                        color: 'rgba(200, 101, 10, 0.12)',
                        borderDash: [4, 4],
                    },
                    ticks: {
                        precision: 0,
                        font: { family: 'Tajawal, Cairo, sans-serif', size: 12 },
                    },
                },
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    rtl: true,
                    backgroundColor: '#3A0A0A',
                    titleColor: '#E8B04B',
                    bodyColor: '#FFF8EE',
                    borderColor: '#C8650A',
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 10,
                    displayColors: false,
                    titleFont: { family: 'Tajawal, Cairo, sans-serif', size: 13, weight: '700' },
                    bodyFont: { family: 'Tajawal, Cairo, sans-serif', size: 13 },
                    callbacks: {
                        label: (ctx) => ` ${ctx.parsed.y} طلب`,
                    },
                },
            },
        }
        JS);
    }
}
