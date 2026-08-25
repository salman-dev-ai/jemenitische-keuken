<?php

namespace App\Livewire;

use App\Models\MenuItem;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class HomePage extends Component
{
    /**
     * استرجاع روائع الأطباق الملكية المميزة (Signature Dishes)
     */
    #[Computed]
    public function signatureDishes()
    {
        return MenuItem::query()
            ->available()       // ✅ تستخدم الـ Scope الموجود
            ->featured()        // ✅ تستخدم الـ Scope الموجود
            ->with('category')
            ->orderBy('sort_order')
            ->take(3)
            ->get();
    }

    public function render()
    {
        return view('livewire.home-page');
    }
}
