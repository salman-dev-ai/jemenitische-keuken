<?php

namespace App\Filament\Resources\Reservations\Tables;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReservationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference_code')
                    ->label('المرجع')
                    ->searchable()
                    ->copyable()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('guest_name')
                    ->label('العميل')
                    ->searchable(),

                TextColumn::make('guest_phone')
                    ->label('الهاتف')
                    ->searchable(),

                TextColumn::make('table.table_number')
                    ->label('الطاولة')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('reservation_date')
                    ->label('التاريخ')
                    ->date('Y-m-d')
                    ->sortable(),

                TextColumn::make('reservation_time')
                    ->label('الوقت')
                    ->time('H:i')
                    ->sortable(),

                TextColumn::make('party_size')
                    ->label('الأشخاص')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('تصفية حسب الحالة')
                    ->badge()
                    // الألوان تُجلب تلقائياً من الـ sReservationStatus Enum إذا طبقنا واجهة HasColor عليه
                    ->sortable(),
            ])->defaultSort('created_at','desc') // ترتيب الحجوزات افتراضياً من الأحدث إلى الأقدم (حسب تاريخ الإدخال)
            ->filters([
                SelectFilter::make('status')
                ->label('تصفية حسب الحالة')
                         ->options(ReservationStatus::class),

            ])


            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),

                Action::make('confirm')
                ->label('تأكيد')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->hidden(fn (Reservation $record )=> $record->status===ReservationStatus::CONFIRMED)
                    ->action(fn (Reservation $record )=> $record->update(['status'=>ReservationStatus::CONFIRMED]))

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
