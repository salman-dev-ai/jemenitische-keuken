<?php

declare(strict_types=1);

namespace App\Filament\Resources\CookieConsents\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

/**
 * جدول سجل الموافقات على الكوكيز (قراءة فقط).
 */
class CookieConsentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('التاريخ والوقت')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

                IconColumn::make('analytics')
                    ->label('التحليلات')
                    ->boolean(),

                IconColumn::make('marketing')
                    ->label('التسويق')
                    ->boolean(),

                TextColumn::make('version')
                    ->label('نسخة السياسة')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('ip_hash')
                    ->label('بصمة IP')
                    ->limit(16)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('uuid')
                    ->label('معرّف الزائر')
                    ->limit(12)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('user_agent')
                    ->label('المتصفح')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('analytics')
                    ->label('التحليلات'),

                TernaryFilter::make('marketing')
                    ->label('التسويق'),
            ])
            ->recordActions([])
            ->toolbarActions([])
            ->defaultSort('created_at', 'desc');
    }
}
