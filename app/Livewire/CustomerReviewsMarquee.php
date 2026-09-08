<?php

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class CustomerReviewsMarquee extends Component
{
    // ✅ بيانات ثابتة للتقييمات (يمكن استبدالها لاحقاً بـ Review Model)
    public array $reviews = [
        [
            'name' => ['ar' => 'أحمد الصبيحي', 'en' => 'Ahmed Al-Subaihi', 'nl' => 'Ahmed'],
            'rating' => 5,
            'date' => ['ar' => 'منذ يومين', 'en' => '2 days ago', 'nl' => '2 dagen geleden'],
            'dish' => ['ar' => 'مندي لحم', 'en' => 'Meat Mandi', 'nl' => 'Vlees Mandi'],
            'comment' => [
                'ar' => 'أطيب مندي جربته في حياتي! لحم ذائب وأرز معطر بجميع الإحساس اليمني الأصيل.',
                'en' => 'Best Mandi I have ever tasted! Tender meat and fragrant rice.',
                'nl' => 'De beste Mandi die ik ooit heb gegeten! Heerlijk mals vlees.',
            ],
            'country' => ['ar' => '🇳🇱 هولندا', 'en' => '🇳🇱 Netherlands', 'nl' => '🇳🇱 Nederland'],
        ],
        [
            'name' => ['ar' => 'سارة المطيري', 'en' => 'Sara Almutairi', 'nl' => 'Sara'],
            'rating' => 5,
            'date' => ['ar' => 'منذ أسبوع', 'en' => '1 week ago', 'nl' => '1 week geleden'],
            'dish' => ['ar' => 'عقدة دجاج', 'en' => 'Chicken Aqda', 'nl' => 'Kip Aqda'],
            'comment' => [
                'ar' => 'الأجواء الساحرة والديوان التراثي يأخذك إلى صنعاء في ثوانٍ. طعام يليق بالضيافة اليمنية الحقيقية.',
                'en' => 'The heritage Majlis atmosphere takes you to Sanaa instantly. Royal Yemeni hospitality!',
                'nl' => 'Heerlijke sfeer en prachtige traditionele inrichting. Een echte aanrader!',
            ],
            'country' => ['ar' => '🇧🇪 بلجيكا', 'en' => '🇧🇪 Belgium', 'nl' => '🇧🇪 België'],
        ],
        [
            'name' => ['ar' => 'يوسف الحكمي', 'en' => 'Yousef Alhakimi', 'nl' => 'Yousef'],
            'rating' => 5,
            'date' => ['ar' => 'منذ شهر', 'en' => '1 month ago', 'nl' => '1 maand geleden'],
            'dish' => ['ar' => 'شفوت ولحوح', 'en' => 'Shafut & Lahoh', 'nl' => 'Shafut & Lahoh'],
            'comment' => [
                'ar' => 'سلطة الفطيرة واللحن الأصيل يذكرني بالبيت. شكراً لكم على حفظ التراث.',
                'en' => 'Fattah and Lahoh remind me of home. Thank you for preserving our culture.',
                'nl' => 'De traditionele broden en salades waren verrukkelijk. Bedankt!',
            ],
            'country' => ['ar' => '🇩🇪 ألمانيا', 'en' => '🇩🇪 Germany', 'nl' => '🇩🇪 Duitsland'],
        ],
        [
            'name' => ['ar' => 'ماريا فان دير بيرغ', 'en' => 'Maria van den Berg', 'nl' => 'Maria van den Berg'],
            'rating' => 5,
            'date' => ['ar' => 'منذ شهرين', 'en' => '2 months ago', 'nl' => '2 maanden geleden'],
            'dish' => ['ar' => 'زربيان عدني', 'en' => 'Adeni Zurbian', 'nl' => 'Adeni Zurbian'],
            'comment' => [
                'ar' => 'تجربة gastronomique استثنائية! كل طبق كان معجزة نكهات. سأعود مراراً وتكراراً.',
                'en' => 'Exceptional culinary experience! Every dish was perfectly balanced.',
                'nl' => 'Buitengewone culinaire ervaring! Elk gerecht was perfect. Kom zeker terug!',
            ],
            'country' => ['ar' => '🇳🇱 أمستردام', 'en' => '🇳🇱 Amsterdam', 'nl' => '🇳🇱 Amsterdam'],
        ],
    ];

    public function getLocalized($value): string
    {
        $locale = app()->getLocale();
        if (is_array($value)) {
            return $value[$locale] ?? $value['en'] ?? $value['ar'] ?? '';
        }

        return $value;
    }

    public function placeholder(): View
    {
        return view('livewire.placeholders.customer-reviews-marquee');
    }

    public function render(): View
    {
        return view('livewire.customer-reviews-marquee');
    }
}
