<?php

namespace App\Livewire;


use App\Models\OrderOption;
use App\Models\Slider;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class HomePage extends Component
{
     
   public function render()
    {
        $loc = app()->getLocale();

        // 1. تجهيز بيانات الشريط المتحرك (الإعلانات)
        $slides = Slider::where('is_active', true)->get()->map(function ($slide) use ($loc) {
            return [
                'image'   => asset('storage/' . $slide->image),
                'eyebrow' => $slide->eyebrow[$loc] ?? $slide->eyebrow['en'] ?? '',
                'title'   => $slide->title[$loc] ?? $slide->title['en'] ?? '',
                'text'    => $slide->subtitle[$loc] ?? $slide->subtitle['en'] ?? '',
            ];
        })->toArray(); // حولناها لـ Array ليتعامل معها Alpine.js بسهولة

        // 2. تجهيز بيانات قسم اطلب أونلاين
        $orderOptions = OrderOption::where('is_active', true)->get()->map(function ($option) use ($loc) {
            return [
                'key'         => $option->icon, // أيقونة Lucide
                'title'       => $option->title[$loc] ?? $option->title['en'] ?? '',
                'description' => $option->description[$loc] ?? $option->description['en'] ?? '',
                'image'       => asset('storage/' . $option->image),
            ];
        });

        // تمرير المتغيرات للواجهة
        return view('livewire.home-page', [
            'slides' => $slides,
            'orderOptions' => $orderOptions,
            'isArabic' => $loc === 'ar',
            'isDutch' => $loc === 'nl',
        ]);
    }

    public function placeholder(): View
    {
        return view('livewire.placeholders.home-page');
    }


}
