<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    protected static ?string $title = 'الطلبات';

    protected static ?string $modelLabel = 'طلب';

    protected static ?string $pluralModelLabel = 'الطلبات';

    // 🔧 التصحيح 1: استخدام Schema بدل Form في التوقيع
    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('customer_name')
                    ->label('اسم العميل')
                    ->required()
                    ->maxLength(255),

                Grid::make(2)->schema([
                    TextInput::make('customer_phone')
                        ->label('رقم الهاتف')
                        ->tel()
                        ->required()
                        ->maxLength(255),

                    TextInput::make('customer_email')
                        ->label('البريد الإلكتروني')
                        ->email()
                        ->maxLength(255),
                ]),

                Grid::make(2)->schema([
                    Select::make('type')
                        ->label('نوع الطلب')
                        ->options(OrderType::class)
                        ->required()
                        ->default(OrderType::PICKUP)
                        ->live(),

                    Select::make('status')
                        ->label('حالة الطلب')
                        ->options(OrderStatus::class)
                        ->required()
                        ->default(OrderStatus::PENDING),
                ]),

                // 📍 حقول عنوان التوصيل مع شرط الإلزام حسب نوع الطلب
                TextInput::make('delivery_address')
                    ->label('عنوان التوصيل')
                    ->maxLength(255)
                    // 🔧 التصحيح 7: إلزام الحقل فقط إذا كان نوع الطلب DELIVERY
                    ->required(fn (callable $get): bool => $get('type') === OrderType::DELIVERY->value)
                    ->default(null),

                Grid::make(2)->schema([
                    TextInput::make('delivery_city')
                        ->label('مدينة التوصيل')
                        ->maxLength(255)
                        ->required(fn (callable $get): bool => $get('type') === OrderType::DELIVERY->value)
                        ->default(null),

                    TextInput::make('delivery_postal_code')
                        ->label('الرمز البريدي للتوصيل')
                        ->maxLength(20)
                        ->required(fn (callable $get): bool => $get('type') === OrderType::DELIVERY->value)
                        ->default(null),
                ]),

                Grid::make(3)->schema([
                    TextInput::make('subtotal')
                        ->label('المجموع')
                        ->numeric()
                        ->prefix('€')
                        ->required(),

                    TextInput::make('tax')
                        ->label('الضريبة')
                        ->numeric()
                        ->prefix('€')
                        ->required(),

                    TextInput::make('total')
                        ->label('الإجمالي')
                        ->numeric()
                        ->prefix('€')
                        ->required(),
                ]),

                Select::make('payment_status')
                    ->label('حالة الدفع')
                    ->options([
                        'unpaid' => 'غير مدفوع',
                        'paid' => 'مدفوع',
                        'refunded' => 'مسترجع',
                    ])
                    ->default('unpaid')
                    ->required(),

                Select::make('payment_method')
                    ->label('طريقة الدفع')
                    ->options([
                        'iDEAL' => 'iDEAL (هولندا)',
                        'Credit Card' => 'بطاقة ائتمان',
                        'Cash' => 'نقداً (كاش)',
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('order_number')
            ->columns([
                TextColumn::make('order_number')
                    ->label('رقم الطلب')
                    ->searchable()
                    ->copyable()
                    ->weight('bold')
                    ->color('primary'),

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
                    ->weight('semibold'),

                TextColumn::make('created_at')
                    ->label('تاريخ الطلب')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('تصفية حسب الحالة')
                    ->options(OrderStatus::class),

                SelectFilter::make('type')
                    ->label('تصفية حسب النوع')
                    ->options(OrderType::class),
            ])
            ->headerActions([
                // 🔧 التصحيح 2: حذف AttachAction (hasMany لا تدعم Attach/Detach)
                CreateAction::make()
                    ->label('إضافة طلب'),
            ])
            ->recordActions([
                EditAction::make(),
                // 🔧 التصحيح 2: حذف DetachAction
                DeleteAction::make()
                    ->label('حذف'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // 🔧 التصحيح 2: حذف DetachBulkAction
                    DeleteBulkAction::make()
                        ->label('حذف المحدد'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}