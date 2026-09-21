<?php

namespace App\Filament\Resources\MenuCategories\Tables;

use App\Models\GalleryItem;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MenuCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
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
                    ->label('الاسم')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('slug')
                    ->label('الرابط الثابت')
                    ->color('gray')
                    ->limit(20),

                ToggleColumn::make('is_available')
                    ->label('مفعل')
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('الترتيب')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('info'),

            ])
            ->filters([
                // فلتر الحالة (متاح / غير متاح)
                SelectFilter::make('is_available')
                    ->label('حالة التفعيل')
                    ->options([
                        true => 'مفعل',
                        false => 'غير مفعل',
                    ])
                    ->default(true),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),

            ])

            //  Enable drag-and-drop sorting directly from the table
            ->reorderable('sort_order')

            ->defaultSort('sort_order', 'asc');
    }
}
