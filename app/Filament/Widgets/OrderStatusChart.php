<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrderStatusChart extends ChartWidget
{
    protected ?string $heading = 'حالة الطلبات';

    protected static ?int $sort = 3;

    protected ?string $maxHeight = '320px';

    protected ?string $pollingInterval = null;

    protected int|string|array $columnSpan = [
        'default' => 1,
        'lg' => 2,
    ];

    /**
     * إجمالي الطلبات يظهر تحت العنوان.
     */
    public function getDescription(): ?string
    {
        $total = Order::query()
            ->whereIn('status', ['pending', 'processing', 'completed', 'cancelled'])
            ->count();

        return 'إجمالي الطلبات: '.number_format($total);
    }

    protected function getData(): array
    {
        // استعلام واحد لتجميع كل الحالات
        $statusCounts = Order::query()
            ->select('status', DB::raw('count(*) as total'))
            ->whereIn('status', ['pending', 'processing', 'completed', 'cancelled'])
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'datasets' => [
                [
                    'label' => 'عدد الطلبات',
                    'data' => [
                        (int) $statusCounts->get('pending', 0),
                        (int) $statusCounts->get('processing', 0),
                        (int) $statusCounts->get('completed', 0),
                        (int) $statusCounts->get('cancelled', 0),
                    ],

                    // ألوان متناسقة مع هوية المطبخ اليمني
                    'backgroundColor' => [
                        '#E8B04B', // ذهبي رملي — قيد الانتظار
                        '#C8650A', // برتقالي محروق — قيد التجهيز
                        '#4F8A5B', // أخضر هادئ — مكتملة
                        '#7A1E1E', // عنابي — ملغاة
                    ],
                    'hoverBackgroundColor' => [
                        '#F2C269',
                        '#E07A12',
                        '#5FA06D',
                        '#942626',
                    ],

                    // بدون حدود بيضاء
                    'borderWidth' => 0,
                    'borderColor' => 'transparent',
                    'borderRadius' => 14,
                    'spacing' => 4,
                    'hoverOffset' => 12,
                ],
            ],
            'labels' => [
                'قيد الانتظار',
                'قيد التجهيز',
                'مكتملة',
                'ملغاة',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array|RawJs
    {
        return RawJs::make(<<<'JS'
        {
            cutout: '74%',
            rotation: -90,
            responsive: true,
            maintainAspectRatio: true,
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 1200,
                easing: 'easeOutQuart',
            },
            layout: { padding: 8 },
            scales: {
                x: { display: false },
                y: { display: false },
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    rtl: true,
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        boxWidth: 10,
                        boxHeight: 10,
                        padding: 18,
                        font: {
                            family: 'Tajawal, Cairo, sans-serif',
                            size: 13,
                            weight: '600',
                        },
                    },
                },
                tooltip: {
                    rtl: true,
                    backgroundColor: '#3A0A0A',
                    titleColor: '#E8B04B',
                    bodyColor: '#FFF8EE',
                    borderColor: '#C8650A',
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 10,
                    displayColors: true,
                    boxPadding: 6,
                    titleFont: { family: 'Tajawal, Cairo, sans-serif', size: 13, weight: '700' },
                    bodyFont: { family: 'Tajawal, Cairo, sans-serif', size: 13 },
                    callbacks: {
                        label: (ctx) => {
                            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                            const pct = total ? Math.round((ctx.parsed / total) * 100) : 0;
                            return ` ${ctx.parsed} طلب (${pct}%)`;
                        },
                    },
                },
            },
        }
        JS);
    }
}
