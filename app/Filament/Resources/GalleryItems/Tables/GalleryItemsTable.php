<?php

namespace App\Filament\Resources\GalleryItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class GalleryItemsTable
{
    // public static function configure(Table $table): Table
    // {
    //     return $table
    //         ->columns([
    //             TextColumn::make('category')
    //                 ->searchable(),
    //             ImageColumn::make('image_path'),

    //             TextColumn::make('thumbnail_path')
    //                 ->searchable(),

    //             TextColumn::make('sort_order')
    //                 ->numeric()
    //                 ->sortable(),
    //             IconColumn::make('is_featured')
    //                 ->boolean(),
    //             IconColumn::make('is_active')
    //                 ->boolean(),
    //             TextColumn::make('views_count')
    //                 ->numeric()
    //                 ->sortable(),
    //             TextColumn::make('created_at')
    //                 ->dateTime()
    //                 ->sortable()
    //                 ->toggleable(isToggledHiddenByDefault: true),
    //             TextColumn::make('updated_at')
    //                 ->dateTime()
    //                 ->sortable()
    //                 ->toggleable(isToggledHiddenByDefault: true),
    //             TextColumn::make('deleted_at')
    //                 ->dateTime()
    //                 ->sortable()
    //                 ->toggleable(isToggledHiddenByDefault: true),
    //         ])
    //         ->filters([
    //             TrashedFilter::make(),
    //         ])
    //         ->recordActions([
    //             EditAction::make(),
    //         ])
    //         ->toolbarActions([
    //             BulkActionGroup::make([
    //                 DeleteBulkAction::make(),
    //                 ForceDeleteBulkAction::make(),
    //                 RestoreBulkAction::make(),
    //             ]),
    //         ]);
    // }


    public static function configure(Table $table): Table
{
    return $table
        ->columns([
            // 1. معاينة الصورة المصغرة بجودة عالية
            ImageColumn::make('image_path')
                ->label('معاينة الصورة')
                ->circular()
               
                ,

            // 2. عنوان الصورة التراثية بالعربي (مع الإنجليزي بالأسفل)
            TextColumn::make('title.ar')
                ->label('عنوان الصورة (عربي)')
                ->searchable()
                ->sortable()
                ->weight('bold')
                ->description(fn ($record): string => $record->title['en'] ?? ''),

            // 3. القسم التراثي مع شارات ملونة وأيقونات
            TextColumn::make('category')
                ->label('القسم التراثي')
                ->badge()
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'mandi'   => '👑 المندي والمظبي الملكي',
                    'pots'    => '🔥 الفخاريات الصنعانية',
                    'majlis'  => '🛋️ الديوان والجلسات',
                    'coffee'  => '☕ ضيافة وبن يماني',
                    'bread'   => '🫓 مخبوزات وملوح',
                    default   => $state,
                })
                ->color(fn (string $state): string => match ($state) {
                    'mandi'   => 'warning',
                    'pots'    => 'danger',
                    'majlis'  => 'success',
                    'coffee'  => 'info',
                    default   => 'gray',
                })
                ->searchable()
                ->sortable(),

            // 4. الشارة التراثية المميزة
            TextColumn::make('badge.ar')
                ->label('الشارة المميزة')
                ->badge()
                ->color('warning')
                ->placeholder('بدون شارة')
                ->toggleable(),

            // 5. زر تبديل العرض المباشر (تفعيل / إخفاء)
            ToggleColumn::make('is_active')
                ->label('معروض في الموقع')
                ->onColor('success')
                ->offColor('danger')
                ->sortable(),

            // 6. زر تمييز في الهيرو والصفحة الرئيسية
            ToggleColumn::make('is_featured')
                ->label('مميز في الهيرو ⭐')
                ->onColor('warning')
                ->sortable(),

            // 7. ترتيب الظهور
            TextColumn::make('sort_order')
                ->label('ترتيب العرض')
                ->numeric()
                ->sortable()
                ->alignCenter(),

            // 8. عدد المشاهدات
            TextColumn::make('views_count')
                ->label('المشاهدات')
                ->numeric()
                ->sortable()
                ->alignCenter()
                ->badge()
                ->color('gray')
                ->toggleable(isToggledHiddenByDefault: true),

            // 9. تاريخ الإضافة
            TextColumn::make('created_at')
                ->label('تاريخ الإنشاء')
                ->dateTime('d/m/Y - h:i A')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            // 10. تاريخ آخر تعديل
            TextColumn::make('updated_at')
                ->label('آخر تحديث')
                ->dateTime('d/m/Y - h:i A')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            // 11. تاريخ الحذف المؤقت
            TextColumn::make('deleted_at')
                ->label('تاريخ الحذف')
                ->dateTime('d/m/Y - h:i A')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->defaultSort('sort_order', 'asc')
        ->filters([
            // تصفية حسب القسم
            SelectFilter::make('category')
                ->label('تصفية حسب القسم التراثي')
                ->options([
                    'mandi'   => '👑 المندي والمظبي الملكي',
                    'pots'    => '🔥 الفخاريات الصنعانية',
                    'majlis'  => '🛋️ الديوان والجلسات التراثية',
                    'coffee'  => '☕ الضيافة والحلويات',
                    'bread'   => '🫓 المخبوزات والملوح',
                ]),

            // سلة المحذوفات
            TrashedFilter::make()
                ->label('سلة المحذوفات'),
        ])
        ->recordActions([
            EditAction::make()
                ->label('تعديل'),
        ])
        ->toolbarActions([
            BulkActionGroup::make([
                DeleteBulkAction::make()
                    ->label('حذف المحدد'),
                ForceDeleteBulkAction::make()
                    ->label('حذف نهائي'),
                RestoreBulkAction::make()
                    ->label('استعادة المحذوف'),
            ])->label('إجراءات جماعية'),
        ])
        ->emptyStateHeading('لا توجد صور في المعرض حالياً')
        ->emptyStateDescription('اضغط على زر "إضافة صورة جديدة" لبدء إضافة صور الأطباق والجلسات.')
        ->emptyStateIcon('heroicon-o-camera');
}
}
