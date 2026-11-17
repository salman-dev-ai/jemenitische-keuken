<?php

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::findOrCreate('super_admin');
    Role::findOrCreate('admin');

    $this->admin = User::factory()->create();
    $this->admin->assignRole('super_admin');
});

test('بعد إنشاء سجل جديد يُعاد المدير إلى جدول القائمة', function () {
    Livewire::actingAs($this->admin)
        ->test(CreateUser::class)
        ->fillForm([
            'name' => 'مدير جديد',
            'email' => 'new-admin@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertRedirect(UserResource::getUrl('index'));
});

test('بعد حفظ تعديل سجل يُعاد المدير إلى جدول القائمة', function () {
    $target = User::factory()->create();
    $target->assignRole('admin');

    Livewire::actingAs($this->admin)
        ->test(EditUser::class, ['record' => $target->getRouteKey()])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertRedirect(UserResource::getUrl('index'));
});
