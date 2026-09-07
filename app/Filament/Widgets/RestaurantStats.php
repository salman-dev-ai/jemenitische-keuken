<?php

namespace App\Filament\Widgets;

use App\Models\GalleryItem;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RestaurantStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {

        // 1. الدقة: حساب الإيرادات للطلبات الفعلية فقط (استبعاد الملغاة)
        $validOrdersCount = Order::query()->where('status', '!=', 'cancelled')->count();
        $totalRevenue = Order::query()->where('status', '!=', 'cancelled')->sum('total');
        return [
            // إجمالي الطلبات
            Stat::make('الطلبات الفعلية', $validOrdersCount)
                ->description('الطلبات المكتملة أو قيد المعالجة')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([3, 5, 7, 4, 12, 8, 15]) // إضافة رسم بياني مصغر (Sparkline) للمظهر الاحترافي
                ->icon('heroicon-o-shopping-bag')
                ->color('success'),


            // إجمالي الإيرادات
            Stat::make('إجمالي الإيرادات', number_format($totalRevenue, 2) . ' €')
                ->description('إيرادات الطلبات المعتمدة')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([5, 8, 10, 15, 12, 20, 25])
                ->icon('heroicon-o-banknotes')
                ->color('warning'),
            // إجمالي الحجوزات
            Stat::make('إجمالي الحجوزات', Reservation::count())
                ->description('حجوزات الطاولات')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->icon('heroicon-o-calendar-days')
                ->color('info'),

// إجمالي المستخدمين
            Stat::make('إجمالي المستخدمين', User::count())
                ->description('العملاء المسجلون')
                ->descriptionIcon('heroicon-m-users')
                ->icon('heroicon-o-users')
                ->color('primary'),

// إجمالي الأطباق
            Stat::make('إجمالي الأطباق', MenuItem::count())
                ->description('الأطباق المتاحة في المنيو')
                ->icon('heroicon-o-sparkles') // تم تعديل الأيقونة لتكون معبرة أكثر
                ->color('danger'),

        // إجمالي أقسام المنيو
            Stat::make('أقسام المنيو', MenuCategory::count())
                ->description('التصنيفات الرئيسية')
                ->icon('heroicon-o-rectangle-stack')
                ->color('gray'),

            // إجمالي الصور
            Stat::make('صور المعرض', GalleryItem::count())
                ->description('صور المطعم والأطباق')
                ->icon('heroicon-o-photo')
                ->color('success'),
        ];
    }
}
