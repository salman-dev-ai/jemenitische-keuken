<?php

namespace App\Filament\Resources\Sliders;

use App\Filament\Resources\SliderResource\Tables\SlidersTable as TablesSlidersTable;
use App\Filament\Resources\Sliders\Pages\CreateSlider;
use App\Filament\Resources\Sliders\Pages\EditSlider;
use App\Filament\Resources\Sliders\Pages\ListSliders;
use App\Filament\Resources\Sliders\Schemas\SliderForm;
use App\Filament\Resources\Sliders\Tables\SlidersTable;
use App\Models\Slider;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;


class SliderResource extends Resource
{
     protected static ?string $model = Slider::class;

      // استخدام Lucide enum بدلاً من Heroicons
    protected static string|BackedEnum|null $navigationIcon = "lucide-megaphone";

    protected static ?string $navigationLabel =  'الشرائح الإعلانية';

    protected static ?string $modelLabel = 'شريحة إعلانية';
    // أضف هذا السطر لتحديد صيغة الجمع باللغة العربية
protected static ?string $pluralModelLabel = 'الشرائح الإعلانية';

    // تفعيل التجميع في القائمة الجانبية
    protected static string|UnitEnum|null $navigationGroup = 'إدارة المحتوى والوسائط';


    /**
     * شارة ذكية تعرض عدد الصور النشطة حالياً في القائمة الجانبية
     */
    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('is_active', true)->count();
    }


    public static function form(Schema $schema): Schema
    {
        return SliderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TablesSlidersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSliders::route('/'),
            'create' => CreateSlider::route('/create'),
            'edit' => EditSlider::route('/{record}/edit'),
        ];
    }
}
