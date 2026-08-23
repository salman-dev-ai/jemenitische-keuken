<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Reservation $reservation): bool
    {
        return $user->hasRole('admin') || $reservation->customer_email === $user->email;
    }

    public function create(?User $user): bool
    {
        // ✅ سماح للضيوف غير المسجلين بإنشاء الحجوزات
        return true;
    }

    public function update(User $user, Reservation $reservation): bool
    {
        return $user->hasRole('admin') || $user->hasRole('manager');
    }

    public function delete(User $user, Reservation $reservation): bool
    {
        return $user->hasRole('admin');
    }
}
