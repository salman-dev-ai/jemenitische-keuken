<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\PaymentAttemptStatus;
use App\Exceptions\PaymentException;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\Payments\PayProService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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

                // ➕ عمود مدينة التوصيل
                TextColumn::make('delivery_city')
                    ->label('مدينة التوصيل')
                    ->placeholder('—')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

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

                // ➕ عمود العميل المسجل
                TextColumn::make('customer.name')
                    ->label('العميل المسجل')
                    ->placeholder('—')
                    ->searchable()
                    ->toggleable()
                    ->color('info'),

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
            // المطبخ يرى الطلب الإلكتروني فقط بعد الدفع
            ->modifyQueryUsing(fn (Builder $query) => $query->where(function (Builder $q): void {
                $q->whereNull('payment_method')
                    ->orWhere('payment_method', '!=', 'online')
                    ->orWhereIn('payment_status', ['paid', 'refunded']);
            }))
            ->poll('1s')
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
                    ->icon('lucide-check')
                    ->color('success')
                    ->hidden(fn (Order $record) => $record->status === OrderStatus::COMPLETED)
                    ->action(fn (Order $record) => $record->update([
                        'status' => OrderStatus::COMPLETED,
                        'payment_status' => 'paid',

                    ])

                    ),
                // زر الاسترداد
                Action::make('refund')
                    ->label('استرداد')
                    ->icon('lucide-undo-2')
                    ->color('warning')
                    ->visible(fn (Order $record): bool => $record->payments()
                        ->where('status', PaymentAttemptStatus::PAID)->get()
                        ->contains(fn ($p): bool => $p->refundableAmount() > 0))
                    ->schema(function (Order $record): array {
                        $payment = $record->payments()->where('status', PaymentAttemptStatus::PAID)->latest('id')->first();
                        $needsBank = in_array($payment?->pay_method, ['bancontact', 'creditcard'], true);

                        return [
                            TextInput::make('amount')
                                ->label('المبلغ (يورو)')
                                ->numeric()->prefix('€')->required()
                                ->default($payment ? $payment->refundableAmount() / 100 : 0),
                            TextInput::make('reason')->label('السبب (داخلي)')->maxLength(255),
                            TextInput::make('account_holder_name')->label('اسم صاحب الحساب')->visible($needsBank)->required($needsBank),
                            TextInput::make('iban')->label('IBAN')->visible($needsBank)->required($needsBank),
                        ];
                    })
                    ->requiresConfirmation()
                    ->action(function (Order $record, array $data): void {
                        $payment = $record->payments()->where('status', PaymentAttemptStatus::PAID)->latest('id')->first();

                        try {
                            app(PayProService::class)->refund(
                                $payment,
                                (int) round(((float) $data['amount']) * 100),
                                $data['reason'] ?? null,
                                null,
                                auth()->id(),
                                array_filter([
                                    'iban' => $data['iban'] ?? null,
                                    'account_holder_name' => $data['account_holder_name'] ?? null,
                                ]),
                            );

                            Notification::make()->title('أُرسل طلب الاسترداد إلى PayPro')->success()->send();
                        } catch (PaymentException $e) {
                            Notification::make()->title($e->getMessage())->danger()->send();
                        }
                    }),

                // 📲 زر دائم لإرسال تحديث حالة الطلب للعميل عبر واتساب (متاح لاحقاً في أي وقت)
                Action::make('send_status_whatsapp')
                    ->label(__('messages.order_status.send_action', [], 'ar'))
                    ->icon('lucide-message-circle')
                    ->color('info')
                    ->visible(
                        fn (?Order $record): bool => $record !== null
                            && in_array($record->status, [OrderStatus::COMPLETED, OrderStatus::CANCELLED], true)
                            && app(OrderService::class)->customerWhatsAppUrl($record) !== null
                    )
                    ->url(fn (?Order $record): ?string => $record === null
                        ? null
                        : app(OrderService::class)->customerWhatsAppUrl($record))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
