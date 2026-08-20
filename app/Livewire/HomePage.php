<?php

namespace App\Livewire;

use App\Models\MenuItem;
use App\Models\MenuCategory;
use Livewire\Component;
use Livewire\Attributes\Computed;

class HomePage extends Component
{
    /**
     * استرجاع روائع الأطباق الملكية المميزة (Signature Dishes)
     */
    #[Computed]
    public function signatureDishes()
    {
        return MenuItem::query()
            ->where('is_available', true)
            ->where('is_featured', true)
            ->with('category')
            ->take(3)
            ->get();
    }

    public function render()
    {
        return view('livewire.home-page', [
            'signatureDishes' => $this->signatureDishes,
        ]);
    }
}
