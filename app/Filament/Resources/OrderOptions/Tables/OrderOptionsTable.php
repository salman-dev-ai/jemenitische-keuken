<?php

namespace App\Filament\Resources\OrderOptions\Tables;

 use Filament\Actions\DeleteAction;
 use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class OrderOptionsTable
{
   public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('الصورة')
                    ->circular()
                    ->imageSize(50),

                TextColumn::make('title')
                    ->label('العنوان')

                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(40),

                TextColumn::make('description')
                    ->label('الوصف')

                    ->toggleable()
                    ->color('gray'),

                TextColumn::make('icon')
                    ->label('الأيقونة')
                    ->badge()
                    ->color('info')
                    ->icon(fn ($state) => match ($state) {
                        'truck'        => 'heroicon-o-truck',
                        'shopping-bag' => 'heroicon-o-shopping-bag',
                        'utensils'     => 'heroicon-o-cake',
                        'clock'        => 'heroicon-o-clock',
                        'map-pin'      => 'heroicon-o-map-pin',
                        'credit-card'  => 'heroicon-o-credit-card',
                        'package'      => 'heroicon-o-cube',
                        'store'        => 'heroicon-o-building-storefront',
                        default        => 'heroicon-o-squares-2x2',
                    }),



                IconColumn::make('is_active')
                    ->label('الحالة')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

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
