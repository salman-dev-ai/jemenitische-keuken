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
                    ->disk('public')
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
                        'truck' => 'lucide-truck',
                        'shopping-bag' => 'lucide-shopping-bag',
                        'utensils' => 'lucide-cake',
                        'clock' => 'lucide-clock',
                        'map-pin' => 'lucide-map-pin',
                        'credit-card' => 'lucide-credit-card',
                        'package' => 'lucide-package',
                        'store' => 'lucide-store',
                        default => 'lucide-layout-grid',
                    }),

                IconColumn::make('is_active')
                    ->label('الحالة')
                    ->boolean()
                    ->trueIcon('lucide-circle-check')
                    ->falseIcon('lucide-circle-x')
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
