<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestOrders extends TableWidget
{
    protected static ?string $heading = 'آخر الطلبات';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->latest()
            )
       ->columns([
    TextColumn::make('order_number')
        ->label('رقم الطلب')
        ->searchable()
        ->sortable(),

    TextColumn::make('customer_name')
        ->label('العميل')
        ->searchable(),

    TextColumn::make('order_type')
        ->label('نوع الطلب')
        ->badge(),

    TextColumn::make('total')
        ->label('الإجمالي')
        ->money('EUR')
        ->sortable(),

    TextColumn::make('status')
        ->label('الحالة')
        ->badge(),

    TextColumn::make('created_at')
        ->label('التاريخ')
        ->dateTime('d/m/Y H:i')
        ->sortable(),
])
            ->paginated(false);
    }
}
