<?php

namespace App\Livewire;

use App\Models\MenuItem;
use Illuminate\View\View;
use Livewire\Component;

class PopularDishes extends Component
{
    public function render(): View
    {
        // الأطباق المميزة التي يختارها المسؤول من لوحة التحكم (بحد أقصى 5).
        $dishes = MenuItem::query()
            ->available()
            ->featured()
            ->orderBy('sort_order')
            ->limit(5)
            ->get();

        return view('livewire.popular-dishes', ['dishes' => $dishes]);
    }
}
