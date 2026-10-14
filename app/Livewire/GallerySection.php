<?php

declare(strict_types=1);

/**
 * مكون قسم المعرض: يجلب العناصر المفعلة مرتبة من قاعدة البيانات مرة واحدة،
 * ثم تتولى Alpine.js في الواجهة التصفية الفورية بين الأقسام دون طلبات إضافية للخادم.
 * يظهر الهيكل العظمي عند أول زيارة فقط، ثم يُتخطى التحميل الكسول.
 */

namespace App\Livewire;

use App\Models\GalleryItem;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class GallerySection extends Component
{
    // مفتاح الجلسة الذي يدل على أن الزائر شاهد التحميل الأولي.
    private const SEEN_KEY = 'gallery.initial_load_done';

    // يحدد هل يُستخدم التحميل الكسول: أول زيارة فقط في هذه الجلسة.
    public static function shouldLazyLoad(): bool
    {
        return ! session()->has(self::SEEN_KEY);
    }

    // يُنفذ بعد اكتمال التحميل الفعلي، فيُسجَّل أن الهيكل عُرض مرة.
    public function mount(): void
    {
        session()->put(self::SEEN_KEY, true);
    }

    // الأقسام التي تحتوي عناصر مفعلة فقط، بنفس ترتيب GalleryItem::CATEGORIES.
    #[Computed]
    public function categories(): Collection
    {
        $locale = (string) app()->getLocale();
        $fallbackLocale = (string) config('app.fallback_locale', 'en');
        $present = $this->galleryItems->pluck('category')->unique()->all();

        return collect(GalleryItem::CATEGORIES)
            ->filter(fn (array $translations, string $key): bool => in_array($key, $present, true))
            ->map(
                fn (array $translations, string $key): array => [
                    'key' => $key,
                    'name' => (string) ($translations[$locale]
                        ?? $translations[$fallbackLocale]
                        ?? $translations['en']
                        ?? $key),
                ],
            )
            ->values();
    }

    /**
     * @return Collection<int, GalleryItem>
     */
    // جلب كل العناصر المفعلة مرتبة، والتصفية بين الأقسام تتم في المتصفح.
    #[Computed]
    public function galleryItems(): Collection
    {
        return GalleryItem::query()
            ->active()
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

    // العنصر المميز للواجهة العلوية، وإلا فأول عنصر مفعل.
    #[Computed]
    public function featuredItem(): ?GalleryItem
    {
        return $this->galleryItems->firstWhere('is_featured', true)
            ?? $this->galleryItems->first();
    }

    // عرض هيكل تحميل مؤقت حتى يجهز المكون.
    public function placeholder(): View
    {
        return view('livewire.placeholders.gallery-section');
    }

    public function render(): View
    {
        return view('livewire.gallery-section');
    }
}
