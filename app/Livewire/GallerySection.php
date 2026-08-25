<?php
declare(strict_types=1);

namespace App\Livewire;

use App\Models\GalleryItem;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class GallerySection extends Component
{
    public string $selectedCategory = 'all';

   public function selectCategory(string $category): void
    {
        // Livewire action parameters come from the client, so do not trust them blindly.
        if ($category === 'all' || array_key_exists($category, GalleryItem::CATEGORIES)) {
            $this->selectedCategory = $category;
        }
    }


 #[Computed]
    public function categories(): Collection
    {
        $locale = (string) app()->getLocale();
        $fallbackLocale = (string) config('app.fallback_locale', 'en');

        return collect(GalleryItem::CATEGORIES)
            ->map(
                fn (array $translations, string $key): array => [
                    'key' => $key,
                    'name' => (string) ($translations[$locale]
                        ?? $translations[$fallbackLocale]
                        ?? $translations['en']
                        ?? $key),
                ],
            )
            ->prepend([
                'key' => 'all',
                'name' => __('messages.menu.all'),
            ])
            ->values();
    }

    /**
     * @return Collection<int, GalleryItem>
     */
    #[Computed]
    public function galleryItems(): Collection
    {
        return GalleryItem::query()
            ->active()
            ->byCategory($this->selectedCategory)
            ->ordered()
            ->get([
                'id',
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
                'created_at',
            ]);
    }

    public function placeholder(): View
    {
        return view('livewire.placeholders.gallery-section');
    }

    public function render(): View
    {
        return view('livewire.gallery-section');
    }
}
