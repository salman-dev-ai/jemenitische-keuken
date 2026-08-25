<?php

namespace App\Filament\Resources\MenuItems\Tables;

use App\Models\MenuItem;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MenuItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn(Builder $query): Builder => $query->with('category')
            )
            ->columns([
                ImageColumn::make('image_path')
                    ->label('الصورة')
                    ->disk('public') // ضروري جداً إذا كانت الصور تُرفع عبر FileUpload الافتراضي
                    ->imageSize(50) // حجم مثالي للعرض الدائري في الجداول
                    ->circular()
                    ->defaultImageUrl('https://placehold.co/100x100/e2e8f0/64748b?text=no img') // للتجربة والعزل
                    ->extraImgAttributes([
                        'class' => 'object-cover',
                        'loading' => 'lazy',
                    ]),

                TextColumn::make('name')
                    ->label('اسم الطبق')
                    ->formatStateUsing(
                        fn(?string $state): string => filled($state)
                            ? $state
                            : '—'
                    )
                    ->searchable(query: function (
                        Builder $query,
                        string $search,
                    ): Builder {
                        return $query->where(function (Builder $query) use ($search): void {
                            foreach (['ar', 'en', 'nl'] as $locale) {
                                $query->orWhere(
                                    "name->{$locale}",
                                    'like',
                                    "%{$search}%",
                                );
                            }
                        });
                    })
                    ->weight('bold'),

                TextColumn::make('category.name')
                    ->label('القسم')
                    ->formatStateUsing(
                        fn(?string $state): string => filled($state)
                            ? $state
                            : '—'
                    )
                    ->badge()
                    ->color('info')
                    ->searchable(query: function (
                        Builder $query,
                        string $search,
                    ): Builder {
                        return $query->whereHas(
                            'category',
                            function (Builder $categoryQuery) use ($search): void {
                                $categoryQuery->where(function (Builder $query) use ($search): void {
                                    foreach (['ar', 'en', 'nl'] as $locale) {
                                        $query->orWhere(
                                            "name->{$locale}",
                                            'like',
                                            "%{$search}%",
                                        );
                                    }
                                });
                            },
                        );
                    }),

                TextColumn::make('price')
                    ->label('السعر')
                    ->money('EUR')
                    ->sortable(),

                ToggleColumn::make('is_spicy')
                    ->label('حار')
                    ->sortable()
                    ->onColor('danger'),

                ToggleColumn::make('is_featured')
                    ->label('مميز')
                    ->sortable()
                    ->onColor('warning'),

                ToggleColumn::make('is_available')
                    ->label('متاح')
                    ->sortable()
                    ->onColor('success')
                    ->offColor('danger'),

                TextColumn::make('sort_order')
                    ->label('الترتيب')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc');
    }
}
