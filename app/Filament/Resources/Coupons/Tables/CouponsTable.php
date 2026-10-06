<?php

declare(strict_types=1);

namespace App\Filament\Resources\Coupons\Tables;

use App\Enums\CouponScope;
use App\Enums\DiscountType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CouponsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('الكود')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('name')
                    ->label('الاسم')
                    ->getStateUsing(fn ($record): string =>
                        $record->getTranslation('name', app()->getLocale())
                        ?? $record->getTranslation('name', 'ar')
                        ?? '—'
                    )
                    ->searchable(),

                TextColumn::make('discount_type')
                    ->label('النوع')
                    ->badge()
                    ->formatStateUsing(fn (DiscountType $state): string => $state->getLabel())
                    ->color(fn (DiscountType $state): string => match ($state) {
                        DiscountType::Percentage => 'info',
                        DiscountType::Fixed => 'warning',
                    }),

                TextColumn::make('discount_value')
                    ->label('القيمة')
                    ->suffix(fn ($record): string =>
                        $record->discount_type === DiscountType::Percentage ? '%' : ' €'
                    )
                    ->sortable(),

                TextColumn::make('applies_to')
                    ->label('النطاق')
                    ->badge()
                    ->formatStateUsing(fn (CouponScope $state): string => $state->getLabel())
                    ->color('gray'),

                TextColumn::make('used_count')
                    ->label('الاستخدام')
                    ->formatStateUsing(fn ($record): string =>
                        $record->max_uses !== null
                            ? "{$record->used_count} / {$record->max_uses}"
                            : "{$record->used_count} / ∞"
                    )
                    ->sortable(),

                TextColumn::make('expires_at')
                    ->label('ينتهي في')
                    ->dateTime('Y-m-d H:i')
                    ->placeholder('بلا نهاية')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('مفعّل')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('discount_type')
                    ->label('نوع الخصم')
                    ->options(DiscountType::options()),

                SelectFilter::make('applies_to')
                    ->label('النطاق')
                    ->options(CouponScope::options()),

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
            ->defaultSort('created_at', 'desc');
    }
}