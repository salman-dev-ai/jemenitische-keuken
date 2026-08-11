<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('رقم الطلب')
                    ->searchable()
                    ->copyable()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('customer_name')
                    ->label('العميل')
                    ->searchable(),

                TextColumn::make('customer_phone')
                    ->label('رقم العميل')
                    ->searchable(),

                TextColumn::make('customer_email')
                    ->searchable(),

            TextColumn::make('type')
                    ->label('النوع')
                    ->badge()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->sortable(),



        TextColumn::make('payment_status')
                    ->label('الدفع')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'danger',
                        'refunded' => 'warning',
                        default => 'gray',
                    }),

              TextColumn::make('total')
                    ->label('الإجمالي')
                    ->money('EUR')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('created_at')
                    ->label('الوقت')
                    ->dateTime('H:i - Y-m-d')
                    ->sortable(),
            ])->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('تصفية حسب الحالة')
                    ->options(OrderStatus::class),

                SelectFilter::make('type')
                    ->label('تصفية حسب نوع الطلب')
                    ->options(OrderType::class),

                SelectFilter::make('payment_status')
                    ->label('تصفية حسب دفع الفاتورة')
                    ->options([
                        'unpaid' => 'غير مدفوع',
                        'paid' => 'مدفوع',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('complete')
                    ->label('إكتمل')
                    ->icon('heroicon-m-check')
                    ->color('success')
                    ->hidden(fn (Order $record) => $record->status === OrderStatus::COMPLETED)
                    ->action(fn (Order $record) => $record->update([
                        'status' => OrderStatus::COMPLETED,
                        'payment_status' => 'paid',
                    ])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
