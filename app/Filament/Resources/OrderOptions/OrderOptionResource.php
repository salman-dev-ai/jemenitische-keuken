<?php

namespace App\Filament\Resources\OrderOptions;

use App\Filament\Resources\OrderOptions\Pages\CreateOrderOption;
use App\Filament\Resources\OrderOptions\Pages\EditOrderOption;
use App\Filament\Resources\OrderOptions\Pages\ListOrderOptions;
use App\Filament\Resources\OrderOptions\Schemas\OrderOptionForm;
use App\Filament\Resources\OrderOptions\Tables\OrderOptionsTable;
use App\Models\OrderOption;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OrderOptionResource extends Resource
{
    protected static ?string $model = OrderOption::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return OrderOptionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrderOptionsTable::configure($table);
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
            'index' => ListOrderOptions::route('/'),
            'create' => CreateOrderOption::route('/create'),
            'edit' => EditOrderOption::route('/{record}/edit'),
        ];
    }
}
