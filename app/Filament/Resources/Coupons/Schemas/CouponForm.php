<?php

declare(strict_types=1);

namespace App\Filament\Resources\Coupons\Schemas;

use App\Enums\CouponScope;
use App\Enums\DiscountType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('coupon_tabs')
                    ->tabs([
                        // ═══ البيانات الأساسية ═══
                        Tab::make('البيانات الأساسية')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                TextInput::make('code')
                                    ->label('كود الكوبون')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(50)
                                    ->alphaDash()
                                    ->helperText('مثال: WELCOME20, SUMMER2026'),

                                Select::make('discount_type')
                                    ->label('نوع الخصم')
                                    ->options(DiscountType::options())
                                    ->required()
                                    ->live()
                                    ->native(false),

                                TextInput::make('discount_value')
                                    ->label('قيمة الخصم')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0.01)
                                    ->step(0.01)
                                    ->suffix(
                                        fn(callable $get): string =>
                                        $get('discount_type') === DiscountType::Percentage->value
                                            ? '%'
                                            : '€'
                                    ),

                                Select::make('applies_to')
                                    ->label('نطاق التطبيق')
                                    ->options(CouponScope::options())
                                    ->default(CouponScope::All->value)
                                    ->required()
                                    ->live()
                                    ->native(false),

                                // يظهر فقط عند اختيار "منتجات محددة"
                                Select::make('menuItems')
                                    ->label('المنتجات المشمولة')
                                    ->relationship('menuItems', 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->visible(
                                        fn(callable $get): bool =>
                                        $get('applies_to') === CouponScope::Products->value
                                    )
                                    ->required(
                                        fn(callable $get): bool =>
                                        $get('applies_to') === CouponScope::Products->value
                                    ),

                                // يظهر فقط عند اختيار "تصنيفات محددة"
                                Select::make('menuCategories')
                                    ->label('التصنيفات المشمولة')
                                    ->relationship('menuCategories', 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->visible(
                                        fn(callable $get): bool =>
                                        $get('applies_to') === CouponScope::Categories->value
                                    )
                                    ->required(
                                        fn(callable $get): bool =>
                                        $get('applies_to') === CouponScope::Categories->value
                                    ),
                            ])
                            ->columns(2),

                        // ═══ الترجمات ═══
                        Tab::make('العربية')
                            ->icon('heroicon-o-language')
                            ->schema([
                                TextInput::make('name.ar')
                                    ->label('الاسم (عربي)')
                                    ->required()
                                    ->maxLength(255),

                                Textarea::make('description.ar')
                                    ->label('الوصف (عربي)')
                                    ->rows(3)
                                    ->maxLength(1000),
                            ]),

                        Tab::make('English')
                            ->icon('heroicon-o-language')
                            ->schema([
                                TextInput::make('name.en')
                                    ->label('Name (English)')
                                    ->required()
                                    ->maxLength(255),

                                Textarea::make('description.en')
                                    ->label('Description (English)')
                                    ->rows(3)
                                    ->maxLength(1000),
                            ]),

                        Tab::make('Nederlands')
                            ->icon('heroicon-o-language')
                            ->schema([
                                TextInput::make('name.nl')
                                    ->label('Naam (Nederlands)')
                                    ->required()
                                    ->maxLength(255),

                                Textarea::make('description.nl')
                                    ->label('Beschrijving (Nederlands)')
                                    ->rows(3)
                                    ->maxLength(1000),
                            ]),

                        // ═══ الحدود والقيود ═══
                        Tab::make('الحدود والصلاحية')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                DateTimePicker::make('expires_at')
                                    ->label('تاريخ انتهاء الصلاحية')
                                    ->nullable()
                                    ->helperText('اتركه فارغًا لعدم وجود تاريخ انتهاء'),

                                TextInput::make('min_order_total')
                                    ->label('الحد الأدنى للطلب')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->suffix('€'),

                                TextInput::make('max_uses')
                                    ->label('الحد الأقصى للاستخدام الكلي')
                                    ->numeric()
                                    ->nullable()
                                    ->minValue(1)
                                    ->helperText('اتركه فارغًا لعدم وجود حد'),

                                TextInput::make('max_uses_per_customer')
                                    ->label('الحد الأقصى لكل عميل')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1),

                                TextInput::make('used_count')
                                    ->label('عدد مرات الاستخدام الحالي')
                                    ->numeric()
                                    ->default(0)
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->visibleOn('edit'),

                                Toggle::make('is_active')
                                    ->label('مفعّل')
                                    ->default(true)
                                    ->inline(false),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
