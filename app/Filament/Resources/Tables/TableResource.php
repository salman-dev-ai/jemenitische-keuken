<?php

namespace App\Filament\Resources\Tables;

use App\Filament\Resources\Tables\Pages\CreateTable;
use App\Filament\Resources\Tables\Pages\EditTable;
use App\Filament\Resources\Tables\Pages\ListTables;
use App\Filament\Resources\Tables\Schemas\TableForm;
use App\Filament\Resources\Tables\Tables\TablesTable;
use App\Models\Table as Table1;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;
use UnitEnum;

class TableResource extends Resource
{
    protected static ?string $model = Table1::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleGroup;
    // protected static string|BackedEnum|null $navigationIcon = 'lucide-rocking-chair';


    protected static ?string $navigationLabel = 'إدارة الطاولات';
    protected static ?string $modelLabel = 'طاولة';
    protected static ?string $pluralModelLabel = 'الطاولات';
    protected static string|UnitEnum|null $navigationGroup = 'إدارة المطعم';

    protected static ?int $navigationSort=2;
    public static function form(Schema $schema): Schema
    {
        return TableForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TablesTable::configure($table);
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
            'index' => ListTables::route('/'),
            'create' => CreateTable::route('/create'),
            'edit' => EditTable::route('/{record}/edit'),
        ];
    }
}
