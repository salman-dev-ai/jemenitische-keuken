<?php

namespace App\Livewire;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

class MenuCategories extends Component
{
    #[Url(as: 'category')]
    public string $selectedCategorySlug = 'all';

    public function selectCategory(string $slug): void
    {
        $this->selectedCategorySlug = $slug;
    }

    /**
     * الأقسام المفعلة مع حساب عدد الأطباق في كل قسم
     */
    #[Computed]
    public function categories()
    {
        return MenuCategory::active()
            ->withCount(['menuItems' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get();
    }

    /**
     * الأطباق المفلترة حسب القسم النشط
     */
    #[Computed]
    public function filteredItems()
    {
        $query = MenuItem::query()->where('is_active', true);

        if ($this->selectedCategorySlug !== 'all') {
            $query->whereHas('category', function ($q) {
                $q->where('slug', $this->selectedCategorySlug);
            });
        }

        return $query->orderBy('sort_order', 'asc')->get();
    }

    public function render()
    {
        return view('livewire.menu-categories', [
            'categories' => $this->categories,
            'items' => $this->filteredItems,
        ]);
    }
}
