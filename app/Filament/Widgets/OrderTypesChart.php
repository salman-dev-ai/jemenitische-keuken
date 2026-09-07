<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrderTypesChart extends ChartWidget
{
    protected ?string $heading = 'أنواع الطلبات';

        protected static ?int $sort = 2;


    protected int|string|array $columnSpan = [
        'default' => 1,
        'lg' => 1,
    ];

    protected function getData(): array
    {
        // 1. تحسين الأداء: استعلام واحد لتجميع البيانات بدلاً من استعلامين منفصلين
        $orderCounts = Order::query()
            ->select('type', DB::raw('count(*) as total'))
            ->where('status', '!=', 'cancelled')
            ->whereIn('type', ['pickup', 'dine_in'])
            ->groupBy('type')
            ->pluck('total', 'type');

        // استخراج القيم مع وضع 0 كقيمة افتراضية في حال عدم وجود طلبات
        $pickup = $orderCounts->get('pickup', 0);
        $dineIn = $orderCounts->get('dine_in', 0);

        return [
            'datasets' => [
                [
                    'label' => 'عدد الطلبات',
                    'data' => [
                        $pickup,
                        $dineIn,
                    ],
                    // 2. تلوين كل جزء من الدائرة بألوان احترافية (Tailwind Colors)
                    'backgroundColor' => [
                        '#3b82f6', // أزرق (Blue 500) لطلبات الاستلام
                        '#10b981', // أخضر (Emerald 500) للطلبات داخل المطعم
                    ],
                    'borderColor' => [
                        '#2563eb', // أزرق داكن للإطار
                        '#059669', // أخضر داكن للإطار
                    ],
                    'borderWidth' => 2,
                    'hoverOffset' => 4, // بروز الجزء عند تمرير الماوس عليه
                ],
            ],
            'labels' => [
                'استلام',
                'داخل المطعم',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    // 3. تحسين المظهر: التحكم في تصميم الرسم البياني
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom', // نقل مفتاح الألوان للأسفل لتوفير المساحة
                ],
            ],
            'cutout' => '75%', // جعل الدائرة المفرغة أنحف لتبدو عصرية وأنيقة
        ];
    }
}
