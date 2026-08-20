<?php

namespace App\Livewire;

use Livewire\Component;

class GallerySection extends Component
{
    public array $images = [
        [
            'image' => 'images/gallery/mandi.jpg',
            'title' => 'المندي الملكي',
            'badge' => 'طبق رئيسي',
            'desc' => 'لحم غنم طري على أرز بسمتي مع بهارات يمانية',
        ],
        // ... باقي الصور
    ];

    public function render()
    {
        return view('livewire.gallery-section');
    }
}
