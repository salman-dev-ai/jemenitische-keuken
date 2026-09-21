<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('المعرف')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->weight('semibold'),

                TextColumn::make('phone')
                    ->label('الهاتف')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->copyable()
                    ->toggleable(),

                TextColumn::make('city')
                    ->label('المدينة')
                    ->searchable()
                    ->toggleable(),

                // 📊 عدد الحجوزات للعميل
                TextColumn::make('reservations_count')
                    ->label('عدد الحجوزات')
                    ->counts('reservations')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                // 📊 عدد الطلبات للعميل
                TextColumn::make('orders_count')
                    ->label('عدد الطلبات')
                    ->counts('orders')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('success'),

                // 📅 تاريخ آخر طلب
                TextColumn::make('last_order_at')
                    ->label('آخر طلب')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('تاريخ التسجيل')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('deleted_at')
                    ->label('تاريخ الحذف')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()
                    ->label('العملاء المحذوفين'),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('تعديل'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('حذف المحدد'),
                    ForceDeleteBulkAction::make()
                        ->label('حذف نهائي'),
                    RestoreBulkAction::make()
                        ->label('استعادة'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}