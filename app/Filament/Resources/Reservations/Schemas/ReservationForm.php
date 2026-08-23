<?php

namespace App\Filament\Resources\Reservations\Schemas;

use App\Enums\ReservationStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReservationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make([
                    'default' => 1,
                    'lg' => 4,
                ])->schema([

                    Grid::make(1)->schema([
                        Section::make('بيانات العميل')
                            ->description('معلومات التواصل مع صاحب الحجز')
                            ->icon('heroicon-o-user')
                            ->schema([
                                TextInput::make('customer_name') // ✅ الاسم الصحيح
                                    ->label('اسم العميل')
                                    ->required()
                                    ->maxLength(255),

                                Grid::make(2)->schema([
                                    TextInput::make('customer_phone') // ✅
                                        ->label('رقم الهاتف')
                                        ->tel()
                                        ->required()
                                        ->maxLength(255),

                                    TextInput::make('customer_email') // ✅
                                        ->label('البريد الإلكتروني')
                                        ->email()
                                        ->maxLength(255),
                                ]),
                            ]),

                        Section::make('تفاصيل الموعد')
                            ->icon('heroicon-o-calendar-days')
                            ->schema([
                                Grid::make(2)->schema([
                                    DatePicker::make('reservation_date')
                                        ->label('تاريخ الحجز')
                                        ->required()
                                        ->native(false)
                                        ->displayFormat('Y-m-d'),

                                    TimePicker::make('reservation_time')
                                        ->label('وقت الحجز')
                                        ->required()
                                        ->seconds(false)
                                        ->datalist([
                                            '12:00', '13:00', '14:00',
                                            '18:00', '19:00', '20:00', '21:00',
                                        ]),
                                ]),

                                Textarea::make('special_requests')
                                    ->label('طلبات خاصة (اختياري)')
                                    ->placeholder('مثال: كرسي أطفال، احتفال بعيد ميلاد...')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpan(['default' => 1, 'lg' => 2]),

                    Grid::make(1)->schema([
                        Section::make('التخصيص والحالة')
                            ->icon('heroicon-o-clipboard-document-check')
                            ->schema([
                                TextInput::make('party_size')
                                    ->label('عدد الأشخاص')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1)
                                    ->maxValue(20),

                                Select::make('status')
                                    ->label('حالة الحجز')
                                    ->options(ReservationStatus::class)
                                    ->required()
                                    ->default(ReservationStatus::PENDING),

                                TextInput::make('reference_code')
                                    ->label('رقم المرجع')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->visibleOn('edit')
                                    ->helperText('تم توليده تلقائياً من قبل النظام.'),
                            ]),
                    ])->columnSpan(['default' => 1, 'lg' => 2]),

                ])->columnSpanFull(),
            ]);
    }
}
