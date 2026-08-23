<?php

namespace App\Livewire;

use App\Models\GalleryItem;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class GallerySection extends Component
{
    public string $selectedCategory = 'all';

    public function selectCategory(string $category): void
    {
        $this->selectedCategory = $category;
    }

    #[Computed]
    public function categories()
    {
        return collect(GalleryItem::CATEGORIES)->map(function ($translations, $key) {
            $locale = app()->getLocale();

            return [
                'key' => $key,
                'name' => $translations[$locale] ?? $translations['en'] ?? $key,
            ];
        })->prepend(['key' => 'all', 'name' => __('messages.menu.all')]);
    }

    #[Computed]
    public function galleryItems()
    {
        return GalleryItem::query()
            ->active()
            ->byCategory($this->selectedCategory)
            ->ordered()
            ->get();
    }

    public function render()
    {
        return view('livewire.gallery-section');
    }
}
