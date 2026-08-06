<?php
 
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
                                TextInput::make('name')->label('name restaurant')->required()->maxLength(255),


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
                        | 3. Operations
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
                            ->columns(2)

                    ])->columnSpanFull(),


            ]);
    }
}
