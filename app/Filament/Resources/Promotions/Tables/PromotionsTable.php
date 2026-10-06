<?php

declare(strict_types=1);

namespace App\Filament\Resources\Promotions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PromotionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('الصورة')
                    ->disk('public')
                    ->height(50)
                    ->width(80),

                TextColumn::make('title')
                    ->label('العنوان')
                    ->getStateUsing(fn ($record): string =>
                        $record->getTranslation('title', app()->getLocale())
                        ?? $record->getTranslation('title', 'ar')
                        ?? '—'
                    )
                    ->searchable()
                    ->limit(50),

                TextColumn::make('coupon.code')
                    ->label('الكوبون')
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                TextColumn::make('coupon.discount_value')
                    ->label('الخصم')
                    ->suffix(fn ($record): string =>
                        $record->coupon?->discount_type?->isPercentage() ? '%' : ' €'
                    )
                    ->sortable(),

                TextColumn::make('starts_at')
                    ->label('يبدأ')
                    ->dateTime('Y-m-d')
                    ->placeholder('فورًا')
                    ->sortable(),

                TextColumn::make('ends_at')
                    ->label('ينتهي')
                    ->dateTime('Y-m-d')
                    ->placeholder('∞')
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('الترتيب')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('مفعّل')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('الحالة'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc');
    }
}