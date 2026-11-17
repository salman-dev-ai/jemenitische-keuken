<?php

namespace App\Filament\Resources\Reservations\Tables;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Services\ReservationService;
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

                TextColumn::make('customer_name')

                    ->label('العميل')
                    ->searchable(),

                TextColumn::make('customer_phone')

                    ->label('الهاتف')
                    ->searchable(),

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
                    ->sortable()
                    ->badge(),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('1s')
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
                    ->icon('lucide-circle-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->hidden(
                        fn (Reservation $record) => $record->status === ReservationStatus::CONFIRMED
                    )
                    ->action(
                        fn (Reservation $record) => $record->update(
                            ['status' => ReservationStatus::CONFIRMED]
                        )
                    ),

                // 📲 زر دائم لإرسال رسالة تأكيد الحجز للعميل عبر واتساب (متاح لاحقاً في أي وقت)
                Action::make('send_confirmation_whatsapp')
                    ->label(__('messages.reservation_confirmation.send_action', [], 'ar'))
                    ->icon('lucide-message-circle')
                    ->color('info')
                    ->visible(
                        fn (?Reservation $record): bool => $record !== null
                            && $record->status === ReservationStatus::CONFIRMED
                            && app(ReservationService::class)->customerWhatsAppUrl($record) !== null
                    )
                    ->url(fn (?Reservation $record): ?string => $record === null
                        ? null
                        : app(ReservationService::class)->customerWhatsAppUrl($record))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
