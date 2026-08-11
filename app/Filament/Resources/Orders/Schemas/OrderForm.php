<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\MenuItem;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make([
                    'default' => 1,
                    'lg' => 12
                ])->schema([

                    // العمود الرئيسي: العميل وعناصر الطلب
                    Grid::make(1)->schema([
                        Section::make('بيانات العميل والطلب')
                            ->icon('heroicon-o-user')
                            ->schema([
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
                                            ->label('الطبق')
                                            ->options(MenuItem::query()->pluck('name', 'id'))
                                            ->required()
                                            ->searchable()
                                            ->reactive()
                                            //   التفاعل الفوري: عند اختيار طبق يتم جلب السعر الافتراضي وتعبئته
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                if ($item = MenuItem::find($state)) {
                                                    $set('unit_price', $item->price);
                                                    $quantity = (int) ($get('quantity') ?? 1);
                                                    $set('total_price', round($item->price * $quantity, 2));
                                                }
                                            })
                                            ->columnSpan(4),

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
