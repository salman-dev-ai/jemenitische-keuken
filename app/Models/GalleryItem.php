<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class GalleryItem extends Model
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
        'is_active',
        'views_count',
    ];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'badge' => 'array',
        'alt_text' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'views_count' => 'integer',
    ];

    /**
     * الفئات التراثية المعتمدة
     */
    public const CATEGORIES = [
        'mandi'   => ['ar' => 'المندي والمظبي 👑', 'en' => 'Mandi & Madhbi', 'nl' => 'Mandi & Madhbi'],
        'pots'    => ['ar' => 'الفخاريات الصنعانية 🔥', 'en' => 'Sizzling Pots', 'nl' => 'Sanani Steenpotten'],
        'majlis'  => ['ar' => 'الديوان والجلسات 🛋️', 'en' => 'Heritage Majlis', 'nl' => 'Traditionele Majlis'],
        'coffee'  => ['ar' => 'الضيافة والحلويات ☕', 'en' => 'Hospitality & Desserts', 'nl' => 'Gastvrijheid & Desserts'],
        'bread'   => ['ar' => 'المخبوزات والملوح 🫓', 'en' => 'Yemeni Breads', 'nl' => 'Ambachtelijk Brood'],
    ];

    /* =========================================================================
       ACCESSORS: الترجمة التلقائية الفورية بحسب لغة الموقع الحالية (AR/EN/NL)
    ========================================================================= */

    public function getTranslatedTitleAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->title[$locale] ?? $this->title['ar'] ?? $this->title['en'] ?? '';
    }

    public function getTranslatedDescAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->description[$locale] ?? $this->description['ar'] ?? $this->description['en'] ?? '';
    }

    public function getTranslatedBadgeAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->badge[$locale] ?? $this->badge['ar'] ?? '';
    }

    public function getImageUrlAttribute(): string
    {
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }
        return Storage::url($this->image_path);
    }

    /* =========================================================================
       SCOPES: نطاقات الاستعلام
    ========================================================================= */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, ?string $category)
    {
        if ($category && $category !== 'all') {
            return $query->where('category', $category);
        }
        return $query;
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }
}
