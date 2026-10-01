<?php

namespace App\Filament\Resources\DeliveryZones;

use App\Filament\Resources\DeliveryZones\Pages\CreateDeliveryZone;
use App\Filament\Resources\DeliveryZones\Pages\EditDeliveryZone;
use App\Filament\Resources\DeliveryZones\Pages\ListDeliveryZones;
use App\Filament\Resources\DeliveryZones\Schemas\DeliveryZoneForm;
use App\Filament\Resources\DeliveryZones\Tables\DeliveryZonesTable;
use App\Models\DeliveryZone;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DeliveryZoneResource extends Resource
{
   protected static ?string $model = DeliveryZone::class;   // ربط المورد بالموديل

    // التصحيح: النوع يجب أن يكون string|BackedEnum|null وليس ?string
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-truck';

    // ملاحظة: $navigationGroup في Filament v5 قد يتطلب أيضًا النوع string|UnitEnum|null
    // إذا ظهر خطأ مشابه، غيّره إلى: protected static string|\UnitEnum|null $navigationGroup = 'Restaurant Settings';
    protected static string|UnitEnum|null $navigationGroup = 'إعدادات المطعم'; // تصنيف المورد في القائمة الجانبية

    protected static ?int $navigationSort = 2;              // ترتيب الظهور في القائمة الجانبية

    /**
     * إرجاع اسم المورد بصيغة المفرد (يظهر في الأزرار والعناوين).
     */
    public static function getModelLabel(): string
    {
        return 'منطقة توصيل';
    }

    /**
     * إرجاع اسم المورد بصيغة الجمع (يظهر في القائمة والعناوين).
     */
    public static function getPluralModelLabel(): string
    {
        return 'مناطق التوصيل';
    }

    /**
     * إرجاع عنوان المورد في القائمة الجانبية.
     */
    public static function getNavigationLabel(): string
    {
        return 'مناطق التوصيل';
    }

    /**
     * بناء نموذج الإدخال (Form) عبر Schema.
     */
    public static function form(Schema $schema): Schema
    {
        return DeliveryZoneForm::configure($schema);
    }

    /**
     * بناء الجدول (Table) عبر Schema.
     */
    public static function table(Table $table): Table
    {
        return DeliveryZonesTable::configure($table);
    }

    /**
     * إرجاع العلاقات (Relation Managers) إن وُجدت.
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => ListDeliveryZones::route('/'),
            'create' => CreateDeliveryZone::route('/create'),
            'edit' => EditDeliveryZone::route('/{record}/edit'),
        ];
    }
}
