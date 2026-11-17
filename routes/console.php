<?php

use App\Models\Order;
use App\Models\User;
use App\Notifications\NewOrderPlaced;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

Artisan::command('debug:notif', function () {
    $this->info('Roles: '.Role::pluck('name')->implode(', '));

    $admin = User::role(['super_admin', 'admin'])->first();
    $this->info('Admin: '.($admin?->email ?? 'NULL'));

    $this->info('Observer linked: '.(app('events')->hasListeners('eloquent.created: '.Order::class) ? 'YES' : 'NO'));

    $this->info('Notifications in DB: '.DB::table('notifications')->count());

    if ($admin) {
        $admin->notify(new NewOrderPlaced(Order::latest()->first()));
        $this->info('Test notification sent. Notifications in DB now: '.DB::table('notifications')->count());
    }
});
