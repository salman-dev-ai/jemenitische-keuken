<?php

use App\Models\Allergen;
use App\Models\MenuItem;
use Database\Seeders\AllergenSeeder;

it('links allergens to a menu item through the pivot without duplication', function () {
    $item = MenuItem::factory()->create();

    // القاموس الافتراضي يُزرع أثناء الترحيل، فنستخدم عناصره الجاهزة.
    $gluten = Allergen::where('slug', 'gluten')->firstOrFail();
    $milk = Allergen::where('slug', 'milk')->firstOrFail();

    $item->allergens()->sync([$gluten->id, $milk->id]);

    // لا تكرار عند إعادة الربط لنفس العناصر
    $item->allergens()->syncWithoutDetaching([$gluten->id]);

    expect($item->fresh()->allergens()->count())->toBe(2)
        ->and($gluten->fresh()->menuItems()->count())->toBe(1);
});

it('only exposes active allergens ordered by sort order', function () {
    $item = MenuItem::factory()->create();

    $visible = Allergen::factory()->create(['is_active' => true, 'sort_order' => 5]);
    $hidden = Allergen::factory()->create(['is_active' => false, 'sort_order' => 1]);

    $item->allergens()->sync([$visible->id, $hidden->id]);

    $names = $item->activeAllergens()->pluck('allergens.id')->all();

    expect($names)->toBe([$visible->id]);
});

it('normalizes icon names to the lucide prefix', function () {
    expect(Allergen::normalizeIcon('wheat'))->toBe('lucide-wheat')
        ->and(Allergen::normalizeIcon('lucide-milk'))->toBe('lucide-milk')
        ->and(Allergen::normalizeIcon('  '))->toBeNull()
        ->and(Allergen::normalizeIcon(null))->toBeNull();
});

it('seeds the default catalog idempotently', function () {
    $this->seed(AllergenSeeder::class);
    $this->seed(AllergenSeeder::class);

    $expected = count(Allergen::DEFAULT_CATALOG);

    expect(Allergen::count())->toBe($expected)
        ->and(Allergen::where('slug', 'gluten')->count())->toBe(1);
});
