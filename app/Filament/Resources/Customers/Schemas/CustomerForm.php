<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make([
                    'default' => 1,
                    'lg' => 2,
                ])->schema([

                    // 📋 القسم الأول: البيانات الأساسية
                    Section::make('البيانات الشخصية')
                        ->description('معلومات التواصل الأساسية للعميل')
                        ->icon('heroicon-o-user')
                        ->schema([

                            TextInput::make('name')
                                ->label('الاسم الكامل')
                                ->placeholder('أدخل اسم العميل')
                                ->required()
                                ->minLength(2)
                                ->maxLength(255),

                            Grid::make(2)->schema([
                                // 🔧 التصحيح 3: إزالة unique() و nullable افتراضياً
                                TextInput::make('email')
                                    ->label('البريد الإلكتروني')
                                    ->placeholder('example@email.com')
                                    ->email()
                                    ->maxLength(255)
                                    ->default(null),

                                // 🔧 التصحيح 3: إزالة required() و unique() من الهاتف (nullable)
                                TextInput::make('phone')
                                    ->label('رقم الهاتف')
                                    ->placeholder('+31 6 12345678')
                                    ->tel()
                                    ->maxLength(255)
                                    ->default(null),
                            ]),

                        ])->columnSpan(['default' => 1, 'lg' => 1]),

                    // 📍 القسم الثاني: بيانات العنوان
                    Section::make('بيانات العنوان')
                        ->description('عنوان التوصيل الافتراضي للعميل')
                        ->icon('heroicon-o-map-pin')
                        ->schema([

                            TextInput::make('address')
                                ->label('العنوان')
                                ->placeholder('مثال: Kerkstraat 123')
                                ->maxLength(255)
                                ->default(null),

                            Grid::make(2)->schema([
                                TextInput::make('city')
                                    ->label('المدينة')
                                    ->placeholder('Amsterdam')
                                    ->maxLength(255)
                                    ->default(null),

                                TextInput::make('postal_code')
                                    ->label('الرمز البريدي')
                                    ->placeholder('1012 NK')
                                    ->maxLength(20)
                                    ->default(null),
                            ]),

                        ])->columnSpan(['default' => 1, 'lg' => 1]),

                ])->columnSpanFull(),
            ]);
    }
}
