<?php

namespace App\Filament\Resources\Tables\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class TablesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('table_number')
                    ->label('رقم الطاولة')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('capacity')
                    ->label('السعة')
                    ->numeric()
                    ->sortable()

                    ->suffix(' أشخاص'), // إضافة نص توضيحي بجانب الرقم

                TextColumn::make('location_zone')
                    ->label('المنطقة')
                    ->searchable()
                    ->badge()
                    // 🏆 ممارسة حديثة: إعطاء ألوان ديناميكية بناءً على المنطقة
                    ->color(fn(string $state): string => match ($state) {
                        'Main Hall' => 'info',
                        'Terrace' => 'success',
                        'Family Section' => 'warning',
                        'VIP Area' => 'danger',
                        default => 'gray',
                    }),



                ToggleColumn::make('is_active')
                    ->label('متاحة')
                    ->sortable(),


            ])
            ->filters([
                // فلتر سريع للبحث بالمنطقة
                SelectFilter::make('location_zone')

                    ->label('تصفية حسب المنطقة')
                    ->options([
                        'Main Hall' => 'القاعة الرئيسية',
                        'Terrace' => 'التراس الخارجي',
                        'Family Section' => 'قسم العائلات',
                        'VIP Area' => 'منطقة كبار الشخصيات',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
