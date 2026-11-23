<?php

use App\Filament\Resources\MenuCategories\Pages\ListMenuCategories;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::findOrCreate('super_admin');

    $this->admin = User::factory()->create();
    $this->admin->assignRole('super_admin');
});

test('يمنع حذف قسم مرتبط بأطباق ويعرض رسالة تنبيه للمدير', function () {
    $category = MenuCategory::factory()->create();
    MenuItem::factory()->create(['menu_category_id' => $category->id]);

    Livewire::actingAs($this->admin)
        ->test(ListMenuCategories::class)
        ->callTableAction('delete', $category)
        ->assertNotified('لا يمكن حذف القسم');

    $this->assertDatabaseHas('menu_categories', ['id' => $category->id]);
});

test('يسمح بحذف قسم غير مرتبط بأي أطباق', function () {
    $category = MenuCategory::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(ListMenuCategories::class)
        ->callTableAction('delete', $category);

    $this->assertDatabaseMissing('menu_categories', ['id' => $category->id]);
});

test('يمنع الحذف الجماعي عندما يكون أحد الأقسام المحددة مرتبطاً بأطباق', function () {
    $blockedCategory = MenuCategory::factory()->create();
    MenuItem::factory()->create(['menu_category_id' => $blockedCategory->id]);
    $freeCategory = MenuCategory::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(ListMenuCategories::class)
        ->callTableBulkAction('delete', [$blockedCategory, $freeCategory])
        ->assertTableBulkActionHalted('delete')
        ->assertNotified('لا يمكن حذف بعض الأقسام');

    $this->assertDatabaseHas('menu_categories', ['id' => $blockedCategory->id]);
    $this->assertDatabaseHas('menu_categories', ['id' => $freeCategory->id]);
});
