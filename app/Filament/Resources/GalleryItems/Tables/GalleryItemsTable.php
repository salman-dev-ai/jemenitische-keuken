<?php

namespace App\Filament\Resources\GalleryItems\Tables;

use App\Models\GalleryItem;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GalleryItemsTable
{
    /**
     * اسم القسم حسب لغة التطبيق الحالية، مع بدائل احتياطية.
     */
    protected static function categoryLabel(string $state): string
    {
        return GalleryItem::CATEGORIES[$state][app()->getLocale()]
            ?? GalleryItem::CATEGORIES[$state]['ar']
            ?? GalleryItem::CATEGORIES[$state]['en']
            ?? $state;
    }

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('الصورة')
                    ->disk('public')
                    ->imageSize(50)
                    ->circular()
                    ->defaultImageUrl('https://placehold.co/100x100/e2e8f0/64748b?text=no+img')
                    ->extraImgAttributes([
                        'class' => 'object-cover',
                        'loading' => 'lazy',
                    ]),

                // عمود JSON: نحدد البحث والترتيب يدوياً لأن "title.ar" لا يُعامل كعمود JSON تلقائياً
                TextColumn::make('title.ar')
                    ->label('عنوان الصورة (عربي)')
                    ->searchable(
                        query: fn (Builder $query, string $search): Builder => $query
                            ->where(fn (Builder $q) => $q
                                ->where('title->ar', 'like', "%{$search}%")
                                ->orWhere('title->en', 'like', "%{$search}%")),
                    )
                    ->sortable(
                        query: fn (Builder $query, string $direction): Builder => $query
                            ->orderBy('title->ar', $direction),
                    )
                    ->weight('bold')
                    ->description(fn (GalleryItem $record): string => $record->title['en'] ?? ''),

                TextColumn::make('category')
                    ->label('القسم التراثي')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => self::categoryLabel($state))
                    ->color(fn (string $state): string => match ($state) {
                        'mandi' => 'warning',
                        'pots' => 'danger',
                        'majlis' => 'success',
                        'coffee' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('badge.ar')
                    ->label('الشارة المميزة')
                    ->badge()
                    ->color('warning')
                    ->placeholder('بدون شارة')
                    ->toggleable(),

                ToggleColumn::make('is_available')
                    ->label('معروض في الموقع')
                    ->onColor('success')
                    ->offColor('danger')
                    ->alignCenter()
                    ->sortable(),

                ToggleColumn::make('is_featured')
                    ->label('مميز في الهيرو ⭐')
                    ->onColor('warning')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('ترتيب العرض')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('d/m/Y - h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('آخر تحديث')
                    ->dateTime('d/m/Y - h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->label('تاريخ الحذف')
                    ->dateTime('d/m/Y - h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order') // السحب والإفلات لتغيير الترتيب
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25)
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->filters([
                SelectFilter::make('category')
                    ->label('تصفية حسب القسم التراثي')
                    ->options(
                        collect(GalleryItem::CATEGORIES)
                            ->mapWithKeys(fn (array $item, string $key): array => [
                                $key => $item['ar'] ?? $item['en'] ?? $key,
                            ])
                            ->all()
                    )
                    ->multiple(),

                // سلة المهملات: تظهر كقائمة "المحذوفات" داخل لوحة الفلاتر
                TrashedFilter::make()
                    ->label('سلة المهملات'),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->recordActions([
                EditAction::make()->label('تعديل'),
                DeleteAction::make()->label('حذف'),
                RestoreAction::make()->label('استعادة'),
                ForceDeleteAction::make()->label('حذف نهائي'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('حذف المحدد'),
                    RestoreBulkAction::make()->label('استعادة المحدد'),
                    ForceDeleteBulkAction::make()->label('حذف نهائي للمحدد'),
                ])->label('إجراءات جماعية'),
            ])
            ->emptyStateHeading('لا توجد صور في المعرض حالياً')
            ->emptyStateDescription('اضغط على زر "إضافة صورة جديدة" لبدء إضافة صور الأطباق والجلسات.')
            ->emptyStateIcon('heroicon-o-camera')
            ->emptyStateActions([
                CreateAction::make()->label('إضافة صورة جديدة'),
            ]);
    }
}
