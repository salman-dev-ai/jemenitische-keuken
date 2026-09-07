<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RestaurantSalesChart extends ChartWidget
{
    // 1. استدعاء Trait الفلاتر المخصصة
    use HasFiltersSchema;

    protected ?string $heading = 'المبيعات';

    protected int|string|array $columnSpan = [
        'default' => 1,
        'lg' => 2,
    ];

    // 2. بناء الهيكل الخارجي للفلتر باستخدام مكونات Filament
    public function filtersSchema(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('period')
                ->hiddenLabel() // إخفاء العنوان لتصميم أنيق
                ->options([
                    '7' => 'آخر 7 أيام',
                    '30' => 'آخر 30 يوم',
                ])
                ->default('7')
                ->native(false) // تحويل القائمة المنسدلة للنمط الأنيق الخاص بـ Filament
                ->selectablePlaceholder(false),
        ]);
    }

    protected function getData(): array
    {
        // 3. استقبال قيمة الفلتر من مصفوفة $this->filters
        $allowedFilters = ['7', '30'];
        $selectedPeriod = $this->filters['period'] ?? '7';
        $days = in_array($selectedPeriod, $allowedFilters) ? (int) $selectedPeriod : 7;

        $startDate = Carbon::today()->subDays($days - 1);
        $endDate = Carbon::today();

        $salesData = Order::query()
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total_sales')
            )
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where('status', '!=', 'cancelled')
            ->groupBy('date')
            ->pluck('total_sales', 'date');

        $labels = [];
        $sales = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateString = $date->format('Y-m-d');

            if ($days <= 7) {
                $labels[] = match ($date->dayOfWeek) {
                    Carbon::SATURDAY => 'السبت',
                    Carbon::SUNDAY => 'الأحد',
                    Carbon::MONDAY => 'الإثنين',
                    Carbon::TUESDAY => 'الثلاثاء',
                    Carbon::WEDNESDAY => 'الأربعاء',
                    Carbon::THURSDAY => 'الخميس',
                    Carbon::FRIDAY => 'الجمعة',
                };
            } else {
                $labels[] = $date->format('d M');
            }

            $sales[] = $salesData->get($dateString) ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'المبيعات',
                    'data' => $sales,
                    'fill' => true,
                    'tension' => 0.4,
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
