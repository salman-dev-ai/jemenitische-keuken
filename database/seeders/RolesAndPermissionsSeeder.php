<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        $superAdmin = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $contentManager = Role::firstOrCreate([
            'name' => 'content_manager',
            'guard_name' => 'web',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [
            // Users
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            // Menu Categories
            'menu_categories.view',
            'menu_categories.create',
            'menu_categories.edit',
            'menu_categories.delete',

            // Menu Items
            'menu_items.view',
            'menu_items.create',
            'menu_items.edit',
            'menu_items.delete',

            // Orders
            'orders.view',
            'orders.create',
            'orders.edit',
            'orders.delete',

            // Reservations
            'reservations.view',
            'reservations.create',
            'reservations.edit',
            'reservations.delete',

            // Gallery
            'gallery.view',
            'gallery.create',
            'gallery.edit',
            'gallery.delete',

            // Restaurant Settings
            'restaurant_settings.view',
            'restaurant_settings.edit',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Super Admin
        |--------------------------------------------------------------------------
        */

        $superAdmin->syncPermissions(
            Permission::all()
        );

        /*
        |--------------------------------------------------------------------------
        | Admin
        |--------------------------------------------------------------------------
        */

        $admin->syncPermissions([
            'menu_categories.view',
            'menu_categories.create',
            'menu_categories.edit',
            'menu_categories.delete',

            'menu_items.view',
            'menu_items.create',
            'menu_items.edit',
            'menu_items.delete',

            'orders.view',
            'orders.create',
            'orders.edit',
            'orders.delete',

            'reservations.view',
            'reservations.create',
            'reservations.edit',
            'reservations.delete',

            'gallery.view',
            'gallery.create',
            'gallery.edit',
            'gallery.delete',

            'restaurant_settings.view',
            'restaurant_settings.edit',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Content Manager
        |--------------------------------------------------------------------------
        */

        $contentManager->syncPermissions([
            'menu_categories.view',
            'menu_categories.create',
            'menu_categories.edit',
            'menu_categories.delete',

            'menu_items.view',
            'menu_items.create',
            'menu_items.edit',
            'menu_items.delete',

            'gallery.view',
            'gallery.create',
            'gallery.edit',
            'gallery.delete',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Super Admin User
        |--------------------------------------------------------------------------
        */

        $user = User::firstOrCreate(
            [
                'email' => 'admin@jemenitische-keuken.nl',
            ],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('ChangeThisPassword123!'),
            ]
        );

        $user->assignRole($superAdmin);
    }
}
