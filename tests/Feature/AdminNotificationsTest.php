<?php

use App\Models\Order;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\NewOrderPlaced;
use App\Notifications\NewReservationCreated;
use App\Observers\OrderObserver;
use App\Observers\ReservationObserver;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Notification::fake();

    Role::findOrCreate('admin');
    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
});

test('admins are notified when an order is created', function () {
    $order = Order::factory()->create();

    (new OrderObserver)->created($order);

    Notification::assertSentTo($this->admin, NewOrderPlaced::class);
});

test('admins are notified when a reservation is created', function () {
    $reservation = Reservation::factory()->create();

    (new ReservationObserver)->created($reservation);

    Notification::assertSentTo($this->admin, NewReservationCreated::class);
});

test('no notification is sent when an admin creates an order manually', function () {
    $this->actingAs($this->admin);

    $order = Order::factory()->create();

    (new OrderObserver)->created($order);

    Notification::assertNothingSent();
});
