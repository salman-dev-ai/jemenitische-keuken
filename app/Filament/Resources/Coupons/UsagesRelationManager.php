<?php

declare(strict_types=1);

namespace App\Filament\Resources\Coupons\RelationManagers;

use App\Enums\CouponUsageStatus;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsagesRelationManager extends RelationManager
{
    protected static string $relationship = 'usages';

    protected static ?string $title = 'سجل الاستخدام';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->label('العميل')
                    ->searchable(),

                TextColumn::make('customer.phone')
                    ->label('الهاتف')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->formatStateUsing(fn (CouponUsageStatus $state): string => $state->getLabel())
                    ->color(fn (CouponUsageStatus $state): string => $state->color()),

                TextColumn::make('discount_amount')
                    ->label('قيمة الخصم')
                    ->money('EUR')
                    ->placeholder('—'),

                TextColumn::make('order.id')
                    ->label('الطلب')
                    ->placeholder('—'),

                TextColumn::make('claimed_at')
                    ->label('تاريخ الحفظ')
                    ->dateTime('Y-m-d H:i'),

                TextColumn::make('used_at')
                    ->label('تاريخ الاستخدام')
                    ->dateTime('Y-m-d H:i')
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('الحالة')
                    ->options(CouponUsageStatus::options()),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }
}