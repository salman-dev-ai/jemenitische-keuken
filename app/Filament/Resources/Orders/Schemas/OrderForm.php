<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Customer;
use App\Models\MenuItem;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class OrderForm
{
    /** نسبة الضريبة (9%) */
    private const TAX_RATE = 0.09;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            // شبكة متجاوبة: عمود واحد على الجوال والتابلت، و 3 أعمدة (2 + 1) على الشاشات الكبيرة
            ->columns([
                'default' => 1,
                'lg' => 3,
            ])
            ->components([
                // العمود الرئيسي
                Group::make()
                    ->schema([
                        self::customerSection(),
                        self::itemsSection(),
                        self::deliverySection(),
                        self::notesSection(),
                    ])
                    ->columnSpan([
                        'default' => 1,
                        'lg' => 2,
                    ]),

                // العمود الجانبي
                Group::make()
                    ->schema([
                        self::statusSection(),
                        self::paymentSection(),
                    ])
                    ->columnSpan([
                        'default' => 1,
                        'lg' => 1,
                    ]),
            ]);
    }

    /* ---------------------------------------------------------------------
     | الأقسام
     |---------------------------------------------------------------------*/

    private static function customerSection(): Section
    {
        return Section::make('بيانات العميل والطلب')
            ->icon('heroicon-o-user')
            ->columns([
                'default' => 1,
                'md' => 2,
            ])
            ->schema([
                Select::make('customer_id')
                    ->label('العميل المسجل')
                    ->placeholder('اختر عميلاً مسجلاً (اختياري)')
                    ->relationship(name: 'customer', titleAttribute: 'name')
                    ->getOptionLabelFromRecordUsing(
                        fn (Customer $record): string => sprintf('%s (%s)', $record->name, $record->phone ?? 'بدون هاتف')
                    )
                    ->searchable(['name', 'email', 'phone'])
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get): void {
                        if (blank($state) || ! $customer = Customer::find($state)) {
                            return;
                        }

                        // الحقل في النموذج => الخاصية في موديل العميل
                        $map = [
                            'customer_name' => 'name',
                            'customer_phone' => 'phone',
                            'customer_email' => 'email',
                            'delivery_address' => 'address',
                            'delivery_city' => 'city',
                            'delivery_postal_code' => 'postal_code',
                        ];

                        foreach ($map as $field => $attribute) {
                            if (blank($get($field))) {
                                $set($field, $customer->{$attribute});
                            }
                        }
                    })
                    ->columnSpanFull(),

                TextInput::make('order_number')
                    ->label('رقم الطلب المرجعي')
                    ->disabled()
                    ->dehydrated(false)
                    ->visibleOn('edit')
                    ->columnSpanFull(),

                TextInput::make('customer_name')
                    ->label('اسم العميل')
                    ->required()
                    ->maxLength(255),

                TextInput::make('customer_phone')
                    ->label('رقم الهاتف')
                    ->tel()
                    ->required()
                    ->maxLength(30),

                TextInput::make('customer_email')
                    ->label('البريد الإلكتروني (اختياري)')
                    ->email()
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    private static function itemsSection(): Section
    {
        return Section::make('الأطباق والوجبات المطلوبة')
            ->icon('heroicon-o-shopping-bag')
            ->schema([
                Repeater::make('items')
                    ->hiddenLabel()
                    ->relationship('items')
                    ->live()
                    ->minItems(1)
                    ->defaultItems(1)
                    ->collapsible()
                    ->cloneable()
                    ->addActionLabel('إضافة طبق آخر')
                    ->itemLabel(fn (array $state): ?string => MenuItem::find($state['menu_item_id'] ?? null)?->localized_name)
                    ->deleteAction(
                        fn (Action $action) => $action->after(
                            fn (Get $get, Set $set) => self::recalculateTotals($get, $set)
                        )
                    )
                    ->columns([
                        'default' => 1,
                        'sm' => 6,
                    ])
                    ->schema([
                        Select::make('menu_item_id')
                            ->label('اختيار الطبق')
                            ->relationship(
                                name: 'menuItem',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query->available()->orderBy('name->ar'),
                            )
                            ->getOptionLabelFromRecordUsing(
                                fn (MenuItem $record): string => sprintf('%s (€%.2f)', $record->localized_name, $record->price)
                            )
                            ->searchable(['name->ar', 'name->en', 'name->nl'])
                            ->preload()
                            ->native(false)
                            ->required()
                            ->live()
                            // منع اختيار نفس الطبق مرتين في الطلب
                            ->distinct()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->afterStateUpdated(function ($state, Set $set, Get $get): void {
                                $unitPrice = (float) (MenuItem::find($state)?->price ?? 0);
                                $quantity = (int) ($get('quantity') ?? 1);

                                $set('unit_price', round($unitPrice, 2));
                                $set('total_price', round($unitPrice * $quantity, 2));

                                self::recalculateTotals($get, $set, '../../');
                            })
                            ->columnSpan([
                                'default' => 1,
                                'sm' => 6,
                            ]),

                        TextInput::make('quantity')
                            ->label('الكمية')
                            ->numeric()
                            ->integer()
                            ->default(1)
                            ->minValue(1)
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Set $set, Get $get): void {
                                $unitPrice = (float) ($get('unit_price') ?? 0);

                                $set('total_price', round($unitPrice * (int) $state, 2));

                                self::recalculateTotals($get, $set, '../../');
                            })
                            ->columnSpan([
                                'default' => 1,
                                'sm' => 2,
                            ]),

                        TextInput::make('unit_price')
                            ->label('سعر الوحدة')
                            ->numeric()
                            ->prefix('€')
                            ->readOnly()
                            ->columnSpan([
                                'default' => 1,
                                'sm' => 2,
                            ]),

                        TextInput::make('total_price')
                            ->label('الإجمالي')
                            ->numeric()
                            ->prefix('€')
                            ->readOnly()
                            ->columnSpan([
                                'default' => 1,
                                'sm' => 2,
                            ]),
                    ]),
            ]);
    }

    private static function deliverySection(): Section
    {
        return Section::make('عنوان التوصيل')
            ->icon('heroicon-o-map-pin')
            ->columns([
                'default' => 1,
                'md' => 2,
            ])
            // يظهر فقط عندما يكون نوع الطلب "توصيل"
            ->visible(fn (Get $get): bool => self::isDelivery($get))
            ->schema([
                TextInput::make('delivery_address')
                    ->label('عنوان التوصيل')
                    ->placeholder('مثال: Kerkstraat 123')
                    ->maxLength(255)
                    ->required(fn (Get $get): bool => self::isDelivery($get))
                    ->columnSpanFull(),

                TextInput::make('delivery_city')
                    ->label('مدينة التوصيل')
                    ->placeholder('Amsterdam')
                    ->maxLength(255)
                    ->required(fn (Get $get): bool => self::isDelivery($get)),

                TextInput::make('delivery_postal_code')
                    ->label('الرمز البريدي للتوصيل')
                    ->placeholder('1012 NK')
                    ->maxLength(20)
                    ->required(fn (Get $get): bool => self::isDelivery($get)),
            ]);
    }

    private static function notesSection(): Section
    {
        return Section::make('ملاحظات')
            ->icon('heroicon-o-chat-bubble-bottom-center-text')
            ->collapsible()
            ->schema([
                Textarea::make('notes')
                    ->label('ملاحظات المطبخ أو العميل')
                    ->rows(3)
                    ->autosize()
                    ->maxLength(1000),
            ]);
    }

    private static function statusSection(): Section
    {
        return Section::make('حالة الطلب والنوع')
            ->icon('heroicon-o-adjustments-horizontal')
            ->schema([
                Select::make('type')
                    ->label('نوع الطلب')
                    ->options(OrderType::class)
                    ->default(OrderType::PICKUP)
                    ->native(false)
                    ->required()
                    // live ليُظهر/يُخفي قسم عنوان التوصيل
                    ->live(),

                Select::make('status')
                    ->label('حالة الطلب')
                    ->options(OrderStatus::class)
                    ->default(OrderStatus::PENDING)
                    ->native(false)
                    ->required(),
            ]);
    }

    private static function paymentSection(): Section
    {
        return Section::make('الدفوعات والحسابات')
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
                    ->native(false)
                    ->required(),

                Select::make('payment_method')
                    ->label('طريقة الدفع')
                    ->options([
                        'iDEAL' => 'iDEAL (هولندا)',
                        'Credit Card' => 'بطاقة ائتمان',
                        'Cash' => 'نقداً (كاش)',
                    ])
                    ->native(false),

                // تُحسب تلقائياً من عناصر الطلب
                TextInput::make('subtotal')
                    ->label('المجموع')
                    ->numeric()
                    ->prefix('€')
                    ->default(0)
                    ->readOnly()
                    ->required(),

                TextInput::make('tax')
                    ->label('الضريبة (9%)')
                    ->numeric()
                    ->prefix('€')
                    ->default(0)
                    ->readOnly()
                    ->required(),

                TextInput::make('total')
                    ->label('الإجمالي النهائي')
                    ->numeric()
                    ->prefix('€')
                    ->default(0)
                    ->readOnly()
                    ->required(),
            ]);
    }

    /* ---------------------------------------------------------------------
     | دوال مساعدة
     |---------------------------------------------------------------------*/

    /** هل نوع الطلب الحالي "توصيل"؟ (يتعامل مع القيمة كنص أو كـ Enum) */
    private static function isDelivery(Get $get): bool
    {
        $type = $get('type');

        if (! $type instanceof OrderType) {
            $type = OrderType::tryFrom((string) $type);
        }

        return $type === OrderType::DELIVERY;
    }

    /**
     * إعادة حساب المجموع والضريبة والإجمالي من عناصر الطلب.
     *
     * @param  string  $prefix  مسار نسبي للوصول إلى الحقول الرئيسية من داخل الـ Repeater ('../../')
     */
    private static function recalculateTotals(Get $get, Set $set, string $prefix = ''): void
    {
        $subtotal = collect($get($prefix.'items') ?? [])
            ->sum(fn (array $item): float => (float) ($item['unit_price'] ?? 0) * (int) ($item['quantity'] ?? 0));

        $tax = round($subtotal * self::TAX_RATE, 2);

        $set($prefix.'subtotal', round($subtotal, 2));
        $set($prefix.'tax', $tax);
        $set($prefix.'total', round($subtotal + $tax, 2));
    }
}
