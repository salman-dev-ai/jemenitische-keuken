<?php

namespace App\Filament\Resources\Allergens\Tables;

use App\Enums\AllergenType;
use App\Models\Allergen;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class AllergensTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('icon')
                    ->label('الأيقونة')
                    ->html()
                    ->formatStateUsing(fn (Allergen $record): HtmlString => self::iconMarkup($record)),

                TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('slug')
                    ->label('الرابط الثابت')
                    ->color('gray')
                    ->limit(25)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('type')
                    ->label('التصنيف')
                    ->badge()
                    ->sortable(),

                TextColumn::make('menu_items_count')
                    ->label('عدد الأطباق')
                    ->counts('menuItems')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('الترتيب')
                    ->numeric()
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('مفعّل')
                    ->sortable()
                    ->onColor('success')
                    ->offColor('danger'),

            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('التصنيف')
                    ->options(AllergenType::options()),

                TernaryFilter::make('is_active')
                    ->label('حالة التفعيل'),
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
            // ترتيب مباشر بالسحب والإفلات على عمود الترتيب
            ->reorderable('sort_order')
            ->defaultSort('sort_order', 'asc');
    }

    /**
     * بناء معاينة العمود: الصورة المرفوعة إن وُجدت، وإلا أيقونة Lucide،
     * وإلا نقطة رمادية كمرجع بصري للعنصر غير المضبوط.
     */
    private static function iconMarkup(Allergen $record): HtmlString
    {
        $classes = 'w-6 h-6 '.($record->isDietaryLabel() ? 'text-emerald-600' : 'text-amber-600');

        if ($record->hasImage()) {
            return new HtmlString(
                '<img src="'.e(Storage::disk('public')->url($record->image_path)).'" alt="'
                .e($record->localized_name).'" class="w-6 h-6 object-contain rounded" loading="lazy" />'
            );
        }

        $icon = $record->iconName();

        if ($icon === null) {
            return new HtmlString('<span class="text-xs text-gray-400">لا أيقونة</span>');
        }

        $markup = rescue(fn (): ?string => svg($icon, $classes)->toHtml(), null, false);

        if ($markup === null) {
            return new HtmlString('<span class="text-xs text-danger-600">'.e($icon).'</span>');
        }

        return new HtmlString('<div class="flex items-center justify-center">'.$markup.'</div>');
    }
}
