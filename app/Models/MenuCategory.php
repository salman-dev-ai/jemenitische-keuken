<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\LaravelPackageTools\Concerns\Package\HasTranslations;


/**
 * Responsibility: Represents a menu category and manages its translated attributes and related items.
 */
class MenuCategory extends Model
{
    use HasTranslations;

    public array $translatable = ['name', 'description'];

    protected $fillable = [

        'name',
        'slug',
        'description',
        'image_path',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order',
        ];
    }

    /**
     * Relationship: A Category has many Menu Items
     */

    public function items(): HasMany
    {
        // استخدام الكلاس مباشرة أفضل من كتابة مساره كنص لضمان التتبع الآمن (Type Safety)
        return $this->hasMany(MenuItem::class);
    }
}
