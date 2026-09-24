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
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
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

                                KeyValue::make('opening_hours')
                                    ->label('ساعات العمل')
                                    ->keyLabel('اليوم')
                                    ->valueLabel('ساعات العمل')
                                    ->keyPlaceholder('مثال: Monday')
                                    ->valuePlaceholder('مثال: 12:00 - 22:00')
                                    ->columnSpanFull(),
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