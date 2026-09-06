<?php

namespace App\Filament\Widgets;

use App\Models\Reservation;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class UpcomingReservations extends TableWidget
{
    protected static ?string $heading = 'الحجوزات القادمة';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Reservation::query()
                    ->whereDate('reservation_date', '>=', today())
                    ->whereNotIn('status', ['cancelled', 'no_show'])
                    ->orderBy('reservation_date')
                    ->orderBy('reservation_time')
            )
            ->columns([
                TextColumn::make('reference_code')
                    ->label('المرجع')
                    ->searchable(),

                TextColumn::make('customer_name')
                    ->label('العميل')
                    ->searchable(),

                TextColumn::make('party_size')
                    ->label('الأشخاص')
                    ->suffix(' أشخاص')
                    ->sortable(),

                TextColumn::make('reservation_date')
                    ->label('التاريخ')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('reservation_time')
                    ->label('الوقت')
                    ->time('H:i')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'قيد الانتظار',
                        'confirmed' => 'مؤكد',
                        'seated' => 'تم الجلوس',
                        'cancelled' => 'ملغي',
                        'no_show' => 'لم يحضر',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'seated' => 'info',
                        'cancelled' => 'danger',
                        'no_show' => 'gray',
                        default => 'gray',
                    }),
            ])
            ->paginated(false);
    }
}
