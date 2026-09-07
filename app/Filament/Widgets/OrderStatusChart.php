<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrderStatusChart extends ChartWidget
{
    protected ?string $heading = 'حالة الطلبات';
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = [
        'default' => 1,
        'lg' => 2,
    ];

    protected function getData(): array
    {
        // 1. تحسين الأداء: استخدام استعلام واحد لتجميع البيانات بدلاً من 4 استعلامات منفصلة
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
                        $statusCounts->get('pending', 0),
                        $statusCounts->get('processing', 0),
                        $statusCounts->get('completed', 0),
                        $statusCounts->get('cancelled', 0),
                    ],
                    // 2. استخدام ألوان دلالية (Semantic Colors) لكل حالة لتبدو احترافية
                    'backgroundColor' => [
                        '#f59e0b', // برتقالي: قيد الانتظار
                        '#3b82f6', // أزرق: قيد التجهيز
                        '#10b981', // أخضر: مكتملة
                        '#ef4444', // أحمر: ملغاة
                    ],
                    'borderColor' => [
                        '#d97706', // حواف داكنة قليلاً لبروز العمود
                        '#2563eb',
                        '#059669',
                        '#dc2626',
                    ],
                    'borderWidth' => 1,
                    'borderRadius' => 4, // جعل زوايا الأعمدة العلوية دائرية قليلاً لمظهر عصري
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
        return 'bar';
    }
}
