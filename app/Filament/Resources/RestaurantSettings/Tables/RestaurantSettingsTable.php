<?php

namespace App\Filament\Resources\RestaurantSettings\Tables;


use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RestaurantSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('اسم المطعم'),

                TextColumn::make('phone')
                    ->label('الهاتف'),

                TextColumn::make('email')
                    ->label('البريد الإلكتروني'),

                IconColumn::make('accepts_reservations')
                    ->label('الحجوزات')

                    ->boolean(), //return value true or false


                IconColumn::make('accepts_online_orders')
                    ->label('الطلبات')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('آخر تحديث')
                    ->dateTime('d/m/Y H:i'),


            ])

            ->recordActions([
                EditAction::make()->label('تعديل'),
            ])
            ->paginated(false)

            // ->toolbarActions([])

        ;
    }
}
