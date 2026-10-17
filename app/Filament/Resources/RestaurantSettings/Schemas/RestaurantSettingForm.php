<?php

declare(strict_types=1);

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * 📄 المسار: app/Filament/Resources/RestaurantSettings/Schemas/RestaurantSettingForm.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 الغرض:
 *    Schema نموذج إعدادات المطعم (Filament 5).
 *    يضيف: نسبة BTW + الحد الأقصى للكمية لكل طبق.
 *
 * 🧩 يعتمد على:
 *    - Filament 5 Schemas API
 *    - LaraZeus\SpatieTranslatable (للترجمة التلقائية عبر التبويبات)
 *
 * ⚠️ تحذيرات مهمة:
 *    - name و address مترجمان — LaraZeus يتعامل مع الترجمة تلقائياً.
 *    - vat_rate يُخزَّن كنسبة مئوية (9.00 = 9%).
 *
 * 🕒 آخر تحديث: 2026-09-24
 * ═══════════════════════════════════════════════════════════════════════════
 */

namespace App\Filament\Resources\RestaurantSettings\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class RestaurantSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('restaurant-settings-tabs')
                    ->tabs([

                        /*
                        |--------------------------------------------------------------------------
                        | 1. Basic Information
                        |--------------------------------------------------------------------------
                        */

                        Tab::make('المعلومات الأساسية')
                            ->icon(Heroicon::OutlinedInformationCircle)
                            ->schema([
                                TextInput::make('name')
                                    ->label('اسم المطعم')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('phone')
                                    ->label('رقم الهاتف')
                                    ->tel()
                                    ->required()
                                    ->maxLength(30),

                                TextInput::make('whatsapp')
                                    ->label('رقم WhatsApp')
                                    ->tel()
                                    ->maxLength(30),

                                TextInput::make('email')
                                    ->label('البريد الإلكتروني')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                            ])->columns(2),

                        /*
                        |--------------------------------------------------------------------------
                        | 2. Location
                        |--------------------------------------------------------------------------
                        */

                        Tab::make('العنوان & الموقع')
                            ->icon(Heroicon::OutlinedMapPin)
                            ->schema([
                                TextInput::make('address')
                                    ->label('العنوان')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('city')
                                    ->label('المدينة')
                                    ->required()
                                    ->maxLength(100),

                                TextInput::make('postal_code')
                                    ->label('الرمز البريدي')
                                    ->required()
                                    ->maxLength(20),

                                TextInput::make('google_maps_link')
                                    ->label('رابط Google Maps')
                                    ->url()
                                    ->maxLength(500)
                                    ->columnSpanFull(),
                            ])
                            ->columns(3),

                        /*
                        |--------------------------------------------------------------------------
                        | 3. Operations & Opening Hours
                        |--------------------------------------------------------------------------
                        */


Tab::make('التشغيل & ساعات العمل')
    ->icon(Heroicon::OutlinedClock)
    ->schema([
        Toggle::make('accepts_reservations')
            ->label('استقبال حجوزات الطاولات')
            ->default(true)
            ->onColor('success')
            ->offColor('danger'),

        Toggle::make('accepts_online_orders')
            ->label('استقبال الطلبات الإلكترونية')
            ->default(true)
            ->onColor('success')
            ->offColor('danger'),

        Repeater::make('opening_hours')
            ->label('ساعات العمل')
            ->helperText('اكتب الوقت بصيغة 12 ساعة مثل 09:30 ثم اختر صباحاً أو مساءً.')
            ->columnSpanFull()
            ->reorderable(false)
            ->minItems(1)
            ->maxItems(7)
            ->addActionLabel('إضافة يوم')
            ->default(collect(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])
                ->map(fn (string $day): array => [
                    'day'           => $day,
                    'is_closed'     => false,
                    'opens_time'    => '12:00',
                    'opens_period'  => 'pm',
                    'closes_time'   => '10:00',
                    'closes_period' => 'pm',
                ])
                ->all())
            ->table([
                TableColumn::make('اليوم')->width('160px'),
                TableColumn::make('مغلق')->width('70px')->alignCenter(),
                TableColumn::make('بداية الدوام')->width('110px'),
                TableColumn::make('صباحاً / مساءً')->width('130px'),
                TableColumn::make('نهاية الدوام')->width('110px'),
                TableColumn::make('صباحاً / مساءً')->width('130px'),
            ])
            ->schema([
                // ── اليوم (قائمة منسدلة) ──
                Select::make('day')
                    ->options([
                        'monday'    => 'الإثنين',
                        'tuesday'   => 'الثلاثاء',
                        'wednesday' => 'الأربعاء',
                        'thursday'  => 'الخميس',
                        'friday'    => 'الجمعة',
                        'saturday'  => 'السبت',
                        'sunday'    => 'الأحد',
                    ])
                    ->native(false)
                    ->searchable()
                    ->required()
                    ->placeholder('اختر اليوم')
                    ->prefixIcon(Heroicon::OutlinedCalendarDays)
                    // منع تكرار نفس اليوم
                    ->disableOptionWhen(
                        fn (string $value, ?string $state, Get $get): bool => $value !== $state
                            && collect($get('../../opening_hours') ?? [])->pluck('day')->contains($value)
                    ),

                Toggle::make('is_closed')
                    ->default(false)
                    ->live()
                    ->onColor('danger'),

                // ── بداية الدوام: كتابة يدوية + صباحاً/مساءً ──
                TextInput::make('opens_time')
                    ->placeholder('09:00')
                    ->mask('99:99')
                    ->maxLength(5)
                    ->regex('/^(0?[1-9]|1[0-2]):[0-5]\d$/')
                    ->validationMessages(['regex' => 'اكتب وقتاً صحيحاً مثل 09:30 (من 1 إلى 12).'])
                    ->extraInputAttributes(['dir' => 'ltr', 'inputmode' => 'numeric'])
                    ->required(fn (Get $get): bool => ! $get('is_closed'))
                    ->disabled(fn (Get $get): bool => (bool) $get('is_closed')),

                Select::make('opens_period')
                    ->options(['am' => 'صباحاً', 'pm' => 'مساءً'])
                    ->native(false)
                    ->selectablePlaceholder(false)
                    ->prefixIcon(Heroicon::OutlinedSun)
                    ->default('am')
                    ->required(fn (Get $get): bool => ! $get('is_closed'))
                    ->disabled(fn (Get $get): bool => (bool) $get('is_closed')),

                // ── نهاية الدوام: كتابة يدوية + صباحاً/مساءً ──
                TextInput::make('closes_time')
                    ->placeholder('10:00')
                    ->mask('99:99')
                    ->maxLength(5)
                    ->regex('/^(0?[1-9]|1[0-2]):[0-5]\d$/')
                    ->validationMessages(['regex' => 'اكتب وقتاً صحيحاً مثل 10:00 (من 1 إلى 12).'])
                    ->extraInputAttributes(['dir' => 'ltr', 'inputmode' => 'numeric'])
                    ->required(fn (Get $get): bool => ! $get('is_closed'))
                    ->disabled(fn (Get $get): bool => (bool) $get('is_closed')),

                Select::make('closes_period')
                    ->options(['am' => 'صباحاً', 'pm' => 'مساءً'])
                    ->native(false)
                    ->selectablePlaceholder(false)
                    ->prefixIcon(Heroicon::OutlinedMoon)
                    ->default('pm')
                    ->required(fn (Get $get): bool => ! $get('is_closed'))
                    ->disabled(fn (Get $get): bool => (bool) $get('is_closed')),
            ]),
    ])
    ->columns(2),
                        /*
                        |--------------------------------------------------------------------------
                        | 4. Tax & Orders (جديد)
                        |--------------------------------------------------------------------------
                        */

                        Tab::make('الضريبة & الطلبات')
                            ->icon(Heroicon::OutlinedBanknotes)
                            ->schema([
                                TextInput::make('vat_rate')
                                    ->label('نسبة الضريبة BTW')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->step(0.01)
                                    ->suffix('%')
                                    ->default(9.00)
                                    ->helperText('الأسعار في القائمة لا تشمل الضريبة — تُضاف في ملخص الطلب. مثال: 9.00 = 9%'),

                                TextInput::make('max_quantity_per_item')
                                    ->label('الحد الأقصى للكمية لكل طبق')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1)
                                    ->maxValue(500)
                                    ->default(50)
                                    ->helperText('أقصى كمية يمكن طلبها من الطبق الواحد (مثال: 50)'),
                            ])
                            ->columns(2),

                    ])->columnSpanFull(),
            ]);
    }
}
