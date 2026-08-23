<?php

namespace App\Livewire;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Lazy]
class MenuCategories extends Component
{
    #[Url(as: 'category')]
    public string $selectedCategorySlug = 'all';

    public function selectCategory(string $slug): void
    {
        $this->selectedCategorySlug = $slug;
    }

    #[Computed]
    public function categories()
    {
        return MenuCategory::active()
            ->withCount(['menuItems' => fn ($q) => $q->available()])
            ->get();
    }

    #[Computed]
    public function filteredItems()
    {
        return MenuItem::query()
            ->available()
            ->with('category') // ✅ التحميل الحريص صحيح في المستوى العلوي
            ->when(
                $this->selectedCategorySlug !== 'all',
                fn ($q) => $q->whereHas(
                    'category',
                    fn ($c) => $c->where('slug', $this->selectedCategorySlug)
                )
            )
            ->orderBy('sort_order', 'asc')
            ->get();
    }

    public function render()
    {
        return view('livewire.menu-categories');
    }
}
