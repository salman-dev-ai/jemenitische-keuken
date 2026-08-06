<?php

namespace App\Filament\Resources\RestaurantSettings;

use App\Filament\Resources\RestaurantSettings\Pages\EditRestaurantSetting;
use App\Filament\Resources\RestaurantSettings\Pages\ListRestaurantSettings;
use App\Filament\Resources\RestaurantSettings\Schemas\RestaurantSettingForm;
use App\Filament\Resources\RestaurantSettings\Tables\RestaurantSettingsTable;
use App\Models\RestaurantSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use UnitEnum;

class RestaurantSettingResource extends Resource
{
    use Translatable;

    protected static ?string $model = RestaurantSetting::class;

    protected static string|BackedEnum|null $navigationIcon =
    Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel =
    'إعدادات المطعم';

    protected static ?string $modelLabel =
    'إعداد المطعم';

    // This is the plural label for the model, used in the navigation
    //  and other places where multiple records are displayed and title
    protected static ?string $pluralModelLabel =
    'إعدادات المطعم';

    protected static string|UnitEnum|null $navigationGroup =
    'النظام';

    // This is the order of the resource in the navigation,
    // lower numbers are displayed first
    protected static ?int $navigationSort = 1;


    // This resource represents singleton restaurant settings.
    // Creating another settings record is not allowed.
    // show the edit page for the first record in the list
    public static function form(Schema $schema): Schema
    {
        return RestaurantSettingForm::configure($schema);
    }

    // This resource represents singleton restaurant settings.
    // show table with the first record in the list
    // and allow editing it
    public static function table(Table $table): Table
    {
        return RestaurantSettingsTable::configure($table);
    }


    public static function getRelations(): array
    {
        return [];
    }

    // navigation  to the edit page  or  the main page of the resource
    public static function getPages(): array
    {
        return [
            'index' => ListRestaurantSettings::route('/'),
            'edit' => EditRestaurantSetting::route('/{record}/edit'),
        ];
    }

    /**
     * This resource represents singleton restaurant settings.
     * Creating another settings record is not allowed.
     */
    public static function canCreate(): bool
    {
        return false;
    }

    /**
     * Restaurant settings should never be deleted from the panel.
     */
    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
