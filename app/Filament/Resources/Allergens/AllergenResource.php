<?php

namespace App\Filament\Resources\Allergens;

use App\Filament\Resources\Allergens\Pages\CreateAllergen;
use App\Filament\Resources\Allergens\Pages\EditAllergen;
use App\Filament\Resources\Allergens\Pages\ListAllergens;
use App\Filament\Resources\Allergens\Schemas\AllergenForm;
use App\Filament\Resources\Allergens\Tables\AllergensTable;
use App\Models\Allergen;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use UnitEnum;

/**
 * إدارة قاموس "معلومات الحساسية والعلامات الغذائية".
 * هذا المورد هو المصدر الوحيد للأسماء والأيقونات التي تُعرض في بطاقات الأطباق.
 */
class AllergenResource extends Resource
{
    use Translatable;

    protected static ?string $model = Allergen::class;

    protected static string|BackedEnum|null $navigationIcon = 'lucide-triangle-alert';

    protected static ?string $navigationLabel = 'الحساسية والعلامات';

    protected static ?string $modelLabel = 'عنصر حساسية';

    protected static ?string $pluralModelLabel = 'معلومات الحساسية والعلامات الغذائية';

    protected static string|UnitEnum|null $navigationGroup = 'إدارة المنيو';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return AllergenForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AllergensTable::configure($table);
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('is_active', true)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAllergens::route('/'),
            'create' => CreateAllergen::route('/create'),
            'edit' => EditAllergen::route('/{record}/edit'),
        ];
    }
}
