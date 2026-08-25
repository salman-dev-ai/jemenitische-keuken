<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

final class GalleryItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gallery_items';

    protected $fillable = [
        'category',
        'title',
        'description',
        'badge',
        'image_path',
        'thumbnail_path',
        'alt_text',
        'sort_order',
        'is_featured',
        'is_available',
        'views_count',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'title' => 'array',
            'description' => 'array',
            'badge' => 'array',
            'alt_text' => 'array',
            'is_featured' => 'boolean',
            'is_available' => 'boolean',
            'sort_order' => 'integer',
            'views_count' => 'integer',
        ];
    }

    /**
     * @var array<string, array<string, string>>
     */
    public const   CATEGORIES = [
        'mandi' => ['ar' => 'المندي والمظبي', 'en' => 'Mandi & Madhbi', 'nl' => 'Mandi & Madhbi'],
        'pots' => ['ar' => 'الفخاريات الصنعانية', 'en' => 'Sizzling Pots', 'nl' => 'Sanani Steenpotten'],
        'majlis' => ['ar' => 'الديوان والجلسات', 'en' => 'Heritage Majlis', 'nl' => 'Traditionele Majlis'],
        'coffee' => ['ar' => 'الضيافة والحلويات', 'en' => 'Hospitality & Desserts', 'nl' => 'Gastvrijheid & Desserts'],
        'bread' => ['ar' => 'المخبوزات والملوح', 'en' => 'Yemeni Breads', 'nl' => 'Ambachtelijk Brood'],
    ];

    protected function localizedName(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->localizedValue($this->title),
        );
    }

    protected function localizedDescription(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->localizedValue($this->description),
        );
    }

    protected function localizedBadge(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->localizedValue($this->badge),
        );
    }

    protected function localizedAlt(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->localizedValue($this->alt_text)
                ?: $this->localizedValue($this->title),
        );
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                $path = trim((string) $this->image_path);

                if ($path === '') {
                    return '';
                }

                return str_starts_with($path, 'http://') || str_starts_with($path, 'https://')
                    ? $path
                    : Storage::url($path);
            },
        );
    }

    /**
     * Backward-compatible aliases for existing callers.
     */
    protected function translatedTitle(): Attribute
    {
        return Attribute::make(get: fn (): string => $this->localized_name);
    }

    protected function translatedDesc(): Attribute
    {
        return Attribute::make(get: fn (): string => $this->localized_description);
    }

    protected function translatedBadge(): Attribute
    {
        return Attribute::make(get: fn (): string => $this->localized_badge);
    }

    private function localizedValue(?array $translations): string
    {
        $translations ??= [];
        $locale = (string) app()->getLocale();
        $fallbackLocale = (string) config('app.fallback_locale', 'en');

        $value = $translations[$locale]
            ?? $translations[$fallbackLocale]
            ?? $translations['en']
            ?? $translations['ar']
            ?? '';

        return is_scalar($value) ? (string) $value : '';
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_available', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory(Builder $query, ?string $category): Builder
    {
        return $category && $category !== 'all'
            ? $query->where('category', $category)
            : $query;
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderBy('sort_order')
            ->latest('created_at');
    }
}
