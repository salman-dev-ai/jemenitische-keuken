<?php

namespace App\Filament\Resources\SliderResource\Tables;

 use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
 use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SlidersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('الصورة')
                    ->circular()
                    ->imageSize(50),

                    TextColumn::make('eyebrow.ar')
                    ->label('النص العلوي')
                    ->limit(20)
                    ->toggleable()
                    ->color('gray'),

                 TextColumn::make('title.ar')
                    ->label('العنوان')

                    ->sortable()
                    ->weight('bold')
                    ->limit(40)
                    ->searchable(),

                // النص الفرعي (اختصار)
                TextColumn::make('subtitle.ar')
                    ->label('النص الفرعي')
                    ->limit(20)
                    ->toggleable()
                    ->color('gray'),



                ToggleColumn::make('is_active')
                    ->label('الحالة')


                     ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('حالة التفعيل')
                    ->placeholder('الكل')
                    ->trueLabel('المفعلة فقط')
                    ->falseLabel('المعطلة فقط'),
            ])
            ->recordActions([
                EditAction::make()
                    ->slideOver()
                    ->label('تعديل'),

                DeleteAction::make()
                    ->label('حذف'),
            ])

            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50]);
    }
}
