<?php

namespace App\Filament\Resources\Tables\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
 use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

class TableForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('معلومات الطاولة')
                    ->description('البيانات الأساسية وموقع الطاولة داخل المطعم')
                    ->icon('heroicon-o-squares-2x2')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('table_number')
                                ->label('رقم الطاولة')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(50)
                                ->placeholder('مثال: T-01')
                                ->helperText('يجب أن يكون رقم الطاولة فريداً.'),

                            TextInput::make('capacity')
                                ->label('سعة الطاولة (عدد الأشخاص)')
                                ->required()
                                ->numeric()
                                ->default(4)
                                ->minValue(1)
                                ->maxValue(20),
                        ]),

                        Select::make('location_zone')
                            ->label('منطقة الطاولة')
                            ->required()
                            ->options([
                                'Main Hall' => 'القاعة الرئيسية (Main Hall)',
                                'Terrace' => 'التراس الخارجي (Terrace)',
                                'Family Section' => 'قسم العائلات (Family Section)',
                                'VIP Area' => 'منطقة كبار الشخصيات (VIP Area)',
                            ])
                            ->native(false) // استخدام تصميم بحث متطور بدلاً من القائمة التقليدية
                            ->searchable(),
                    ]),

                Section::make('حالة الطاولة')
                    ->icon('heroicon-o-check-badge')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('متاحة للحجز')
                            ->default(true)
                            ->inline(false)
                            ->onColor('success')
                            ->offColor('danger')
                            ->helperText('عطل هذا الخيار إذا كانت الطاولة تحت الصيانة.'),
                    ])->columns(2)
            ])->columns(2);
    }
}
