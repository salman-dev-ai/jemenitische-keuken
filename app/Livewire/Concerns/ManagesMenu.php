<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;

/**
 * Trait: عرض القائمة (الأطباق والتصنيفات والفلترة).
 *
 * 🎯 المسؤولية: كل ما يخص قراءة الأطباق والتصنيفات من قاعدة البيانات
 *    وتغيير التصنيف المحدد. لا يعرف شيئاً عن السلة أو الدفع.
 *
 * ⚠️ يعتمد على خاصية المكوّن: public string $selectedCategorySlug.
 */
trait ManagesMenu
{
    /**
     * كل الأطباق المتاحة مرتبة حسب sort_order (مع slug التصنيف فقط لتخفيف الاستعلام).
     *
     * @return Collection<int, MenuItem>
     */
    #[Computed]
    public function menuItems(): Collection
    {
        return MenuItem::query()
            ->where('is_available', true)
            ->with('category:id,slug') // اسم العلاقة في الموديل
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * تغيير التصنيف المحدد وإبطال كاش الأطباق المفلترة ليُعاد حسابها.
     */
    public function selectCategory(string $slug): void
    {
        $this->selectedCategorySlug = $slug;
        unset($this->filteredItems);
    }

    /**
     * التصنيفات الفعّالة مع عدد الأطباق المتاحة في كل تصنيف.
     * persist: true ⇒ تُحفظ بين الطلبات لأنها نادراً ما تتغير.
     *
     * @return Collection<int, MenuCategory>
     */
    #[Computed(persist: true)]
    public function categories(): Collection
    {
        return MenuCategory::active()
            ->withCount(['menuItems' => fn ($q) => $q->available()])
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * الأطباق المتاحة بعد تطبيق فلتر التصنيف المحدد ('all' = بلا فلتر).
     *
     * @return Collection<int, MenuItem>
     */
    #[Computed]
    public function filteredItems(): Collection
    {
        return MenuItem::available()
            ->with('category')
            ->when(
                $this->selectedCategorySlug !== 'all',
                fn ($q) => $q->whereHas(
                    'category',
                    fn ($c) => $c->where('slug', $this->selectedCategorySlug),
                ),
            )
            ->orderBy('sort_order')
            ->get();
    }
}
