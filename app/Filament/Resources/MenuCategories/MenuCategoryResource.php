<?php

namespace App\Filament\Resources\MenuCategories;

use App\Filament\Resources\MenuCategories\Pages\CreateMenuCategory;
use App\Filament\Resources\MenuCategories\Pages\EditMenuCategory;
use App\Filament\Resources\MenuCategories\Pages\ListMenuCategories;
use App\Filament\Resources\MenuCategories\Schemas\MenuCategoryForm;
use App\Filament\Resources\MenuCategories\Tables\MenuCategoriesTable;
use App\Models\MenuCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

use UnitEnum;

class MenuCategoryResource extends Resource
{
    use Translatable;
    protected static ?string $model = MenuCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;


    protected static ?string $navigationLabel = 'أقسام المنيو';
    protected static ?string $modelLabel = 'انشاء قسم ';
    protected static ?string $pluralModelLabel = 'أقسام المنيو';
    protected static string|UnitEnum|null $navigationGroup = 'إدارة المنيو';

    protected static ?int $navigationSort=1;
    public static function form(Schema $schema): Schema
    {
        return MenuCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MenuCategoriesTable::configure($table);
    }

    public static function getHeaderAction(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenuCategories::route('/'),
            'create' => CreateMenuCategory::route('/create'),
            'edit' => EditMenuCategory::route('/{record}/edit'),


        ];
    }
}
