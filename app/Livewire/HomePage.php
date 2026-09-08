<?php

namespace App\Livewire;

use App\Models\MenuItem;
use Illuminate\View\View;
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
            ->available()
            ->featured()
            ->with('category')
            ->orderBy('sort_order')
            ->take(3)
            ->get();
    }

    public function placeholder(): View
    {
        return view('livewire.placeholders.home-page');
    }

    public function render(): View
    {
        return view('livewire.home-page');
    }
}
