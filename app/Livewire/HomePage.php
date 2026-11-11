<?php

namespace App\Livewire;

use App\Models\OrderOption;
use App\Models\Slider;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Livewire\Attributes\Lazy;
use Livewire\Component;

// #[Lazy]
class HomePage extends Component
{
    public function render()
    {
        $loc = app()->getLocale();

        // 1. تجهيز بيانات الشريط المتحرك (الإعلانات)
        $slides = Slider::where('is_active', true)->get()->map(function ($slide) use ($loc) {
            return [
                'image' => asset('storage/'.$slide->image),
                'eyebrow' => $slide->eyebrow[$loc] ?? $slide->eyebrow['en'] ?? '',
                'title' => $slide->title[$loc] ?? $slide->title['en'] ?? '',
                'text' => $slide->subtitle[$loc] ?? $slide->subtitle['en'] ?? '',
            ];
        })->toArray(); // حولناها لـ Array ليتعامل معها Alpine.js بسهولة

        // 2. تجهيز بيانات قسم اطلب أونلاين
        $orderOptions = OrderOption::where('is_active', true)->get()->map(function ($option) use ($loc) {
            return [
                'key' => $option->icon, // أيقونة Lucide
                'title' => $option->title[$loc] ?? $option->title['en'] ?? '',
                'description' => $option->description[$loc] ?? $option->description['en'] ?? '',
                'image' => asset('storage/'.$option->image),
            ];
        });

        // 3. بيانات قسم «من نحن» من ملفات الترجمة
        $about = [
            'title' => __('about.title'),
            'intro' => __('about.intro'),
            'body' => implode("\n\n", (array) __('about.body')),
            'image' => file_exists(public_path('images/home/home_logo.webp')) ? asset('images/home/home_logo.webp') : null,
            'url' => Route::has('about') ? route('about') : null,
            'features' => collect((array) __('about.features'))->map(fn ($label) => ['label' => $label])->all(),
        ];

        // تمرير المتغيرات للواجهة
        return view('livewire.home.home-page', [
            'slides' => $slides,
            'orderOptions' => $orderOptions,
            'isArabic' => $loc === 'ar',
            'isDutch' => $loc === 'nl',
            'about' => $about,
        ]);
    }

    public function placeholder(): View
    {
        return view('livewire.placeholders.home-page');
    }
}
