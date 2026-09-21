<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\MenuItem;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make([
                    'default' => 1,
                    'lg' => 12,
                ])->schema([

                    // العمود الرئيسي: العميل وعناصر الطلب
                    Grid::make(1)->schema([
                        Section::make('بيانات العميل والطلب')
                            ->icon('heroicon-o-user')
                            ->schema([

                                // ➕ حقل اختيار العميل المسجل (جديد)
                                Select::make('customer_id')
                                    ->label('العميل المسجل')
                                    ->placeholder('اختر عميلاً مسجلاً (اختياري)')
                                    ->relationship(
                                        name: 'customer',
                                        titleAttribute: 'name',
                                    )
                                    ->getOptionLabelFromRecordUsing(
                                        fn($record) => sprintf('%s (%s)', $record->name, $record->phone ?? 'بدون هاتف')
                                    )
                                    ->searchable(['name', 'email', 'phone'])
                                    ->preload()
                                    ->native(false)
                                    ->live()
                                    // ✨ تعبئة البيانات تلقائياً وعنوان التوصيل
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state && $customer = \App\Models\Customer::find($state)) {
                                            if (blank($get('customer_name'))) {
                                                $set('customer_name', $customer->name);
                                            }
                                            if (blank($get('customer_phone'))) {
                                                $set('customer_phone', $customer->phone);
                                            }
                                            if (blank($get('customer_email'))) {
                                                $set('customer_email', $customer->email);
                                            }
                                            // تعبئة عنوان التوصيل من بيانات العميل
                                            if (blank($get('delivery_address'))) {
                                                $set('delivery_address', $customer->address);
                                            }
                                            if (blank($get('delivery_city'))) {
                                                $set('delivery_city', $customer->city);
                                            }
                                            if (blank($get('delivery_postal_code'))) {
                                                $set('delivery_postal_code', $customer->postal_code);
                                            }
                                        }
                                    }),
                                TextInput::make('order_number')
                                    ->label('رقم الطلب المرجعي')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->visibleOn('edit'),

                                Grid::make(['default' => 1, 'md' => 2])->schema([
                                    TextInput::make('customer_name')
                                        ->label('اسم العميل')
                                        ->required()
                                        ->maxLength(255),

                                    TextInput::make('customer_phone')
                                        ->label('رقم الهاتف')
                                        ->tel()
                                        ->required(),
                                ]),

                                TextInput::make('customer_email')
                                    ->label('البريد الإلكتروني (اختياري)')
                                    ->email(),

                            ]),

                        Section::make('الأطباق والوجبات المطلوبة')
                            ->icon('heroicon-o-shopping-bag')
                            ->schema([
                                Repeater::make('items')
                                    ->relationship('items')
                                    ->schema([
                                        Select::make('menu_item_id')
                                            ->label('اختيار الطبق')
                                            ->relationship(
                                                name: 'menuItem',
                                                titleAttribute: 'name',
                                                modifyQueryUsing: fn(Builder $query) => $query->available()->orderBy('name->ar'),
                                            )
                                            ->getOptionLabelFromRecordUsing(
                                                fn(MenuItem $record) => sprintf(
                                                    '%s (€%.2f)',
                                                    $record->localized_name,
                                                    $record->price
                                                )
                                            )
                                            ->searchable(['name->ar', 'name->en', 'name->nl'])
                                            ->preload()
                                            ->required()
                                            ->columnSpan(4),
                                                                            Select::make('type')
                                    ->label('نوع الطلب')
                                    ->options(OrderType::class)
                                    ->required()
                                    ->default(OrderType::PICKUP)
                                    // 🆕 تفعيل الـ live لتطبيق الشرط على حقول العنوان
                                    ->live(),

                                        TextInput::make('quantity')
                                            ->label('الكمية')
                                            ->numeric()
                                            ->default(1)
                                            ->required()
                                            ->minValue(1)
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                $unitPrice = (float) ($get('unit_price') ?? 0);
                                                $set('total_price', round($unitPrice * (int) $state, 2));
                                            })
                                            ->columnSpan(2),

                                        TextInput::make('unit_price')
                                            ->label('سعر الوحدة')
                                            ->numeric()
                                            ->prefix('€')
                                            ->readOnly()
                                            ->columnSpan(3),

                                        TextInput::make('total_price')
                                            ->label('الإجمالي')
                                            ->numeric()
                                            ->prefix('€')
                                            ->readOnly()
                                            ->columnSpan(3),

                                                                            // 📍 حقول عنوان التوصيل (جديد مع شرط الإلزام)
                                TextInput::make('delivery_address')
                                    ->label('عنوان التوصيل')
                                    ->placeholder('مثال: Kerkstraat 123')
                                    ->maxLength(255)
                                    // 🔧 التصحيح 7: إلزامي فقط إذا كان نوع الطلب = DELIVERY
                                    ->required(fn (callable $get): bool => $get('type') === OrderType::DELIVERY->value)
                                    ->default(null)
                                    ->columnSpanFull(),

                                Grid::make(['default' => 1, 'md' => 2])->schema([
                                    TextInput::make('delivery_city')
                                        ->label('مدينة التوصيل')
                                        ->placeholder('Amsterdam')
                                        ->maxLength(255)
                                        ->required(fn (callable $get): bool => $get('type') === OrderType::DELIVERY->value)
                                        ->default(null),

                                    TextInput::make('delivery_postal_code')
                                        ->label('الرمز البريدي للتوصيل')
                                        ->placeholder('1012 NK')
                                        ->maxLength(20)
                                        ->required(fn (callable $get): bool => $get('type') === OrderType::DELIVERY->value)
                                        ->default(null),
                                ])->columnSpanFull(),
                                    ])
                                    ->columns(12)
                                    ->defaultItems(1)
                                    ->addActionLabel('إضافة طبق آخر'),
                            ]),

                        Section::make('ملاحظات')
                            ->collapsible()
                            ->schema([
                                Textarea::make('notes')
                                    ->label('ملاحظات المطبخ أو العميل')
                                    ->rows(1),
                            ]),
                    ])->columnSpan(['default' => 1, 'lg' => 8]),

                    // العمود الجانبي: حالة الحسابات والمالية
                    Grid::make(1)->schema([
                        Section::make('حالة الطلب والنوع')
                            ->icon('heroicon-o-adjustments-horizontal')
                            ->schema([
                                Select::make('type')
                                    ->label('نوع الطلب')
                                    ->options(OrderType::class)
                                    ->required()
                                    ->default(OrderType::PICKUP),

                                Select::make('status')
                                    ->label('حالة الطلب')
                                    ->options(OrderStatus::class)
                                    ->required()
                                    ->default(OrderStatus::PENDING),
                            ]),

                        Section::make('الدفوعات والحسابات')
                            ->icon('heroicon-o-banknotes')
                            ->schema([
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

                                Grid::make(2)->schema([
                                    TextInput::make('subtotal')
                                        ->label('المجموع')
                                        ->numeric()
                                        ->prefix('€')
                                        ->required(),

                                    TextInput::make('tax')
                                        ->label('الضريبة (9%)')
                                        ->numeric()
                                        ->prefix('€')
                                        ->required(),
                                ]),

                                TextInput::make('total')
                                    ->label('الإجمالي النهائي')
                                    ->numeric()
                                    ->prefix('€')
                                    ->required(),
                            ]),

                    ])->columnSpan(['default' => 1, 'lg' => 4])->columnSpanFull(),

                ])->columnSpanFull(),
            ]);
    }
}
