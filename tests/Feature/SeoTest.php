<?php

use App\Models\RestaurantSetting;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| اختبارات السيو (SEO)
|--------------------------------------------------------------------------
| تغطّي: تحويل الجذر، وسوم الميتا وcanonical، روابط hreflang، Open Graph،
| البيانات المنظّمة JSON-LD، خريطة الموقع، robots، وتجاوزات لوحة التحكم.
*/

beforeEach(function () {
    $this->withoutVite();

    // جدول الشرائح (sliders) يُدار خارج المشروع في الإنتاج ولا يملك ملف إنشاء.
    // ننشئ نسخة مصغّرة في بيئة الاختبار فقط حتى تُرسم الصفحة الرئيسية دون فشل.
    if (! Schema::hasTable('sliders')) {
        Schema::create('sliders', function (Blueprint $table): void {
            $table->id();
            $table->string('image')->nullable();
            $table->json('eyebrow')->nullable();
            $table->json('title')->nullable();
            $table->json('subtitle')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
});

it('redirects the root url to the default locale', function () {
    $this->get('/')->assertRedirect('/nl');
});

it('renders meta, canonical, hreflang, open graph and json-ld on the home page', function () {
    $homeNl = route('home', ['locale' => 'nl']);

    $this->get('/nl')
        ->assertOk()
        // الوصف والرابط الأساسي
        ->assertSee('<meta name="description"', false)
        ->assertSee('<link rel="canonical" href="'.$homeNl.'"', false)
        // روابط اللغات البديلة (hreflang) + x-default
        ->assertSee('hreflang="nl"', false)
        ->assertSee('hreflang="en"', false)
        ->assertSee('hreflang="ar"', false)
        ->assertSee('hreflang="x-default" href="'.$homeNl.'"', false)
        // Open Graph
        ->assertSee('property="og:type" content="restaurant"', false)
        ->assertSee('property="og:url" content="'.$homeNl.'"', false)
        // البيانات المنظّمة
        ->assertSee('application/ld+json', false)
        ->assertSee('"@type":"Restaurant"', false);
});

it('uses the restaurant meta overrides from the admin panel on the home page', function () {
    RestaurantSetting::current()->update([
        'meta_title' => ['nl' => 'Titel uit het dashboard', 'en' => 'Dashboard title', 'ar' => 'عنوان من اللوحة'],
        'meta_description' => ['nl' => 'Beschrijving uit het dashboard.', 'en' => 'Dashboard description.', 'ar' => 'وصف من اللوحة.'],
    ]);

    $this->get('/nl')
        ->assertOk()
        ->assertSee('<title>Titel uit het dashboard</title>', false)
        ->assertSee('content="Beschrijving uit het dashboard."', false);
});

it('falls back to the language-file meta on the secondary pages', function () {
    $this->get('/nl/restaurant-info')
        ->assertOk()
        ->assertSee('<title>'.trans('seo.pages.restaurant-info.title', [], 'nl').'</title>', false);

    $this->get('/en/restaurant-info')
        ->assertOk()
        ->assertSee('<title>'.trans('seo.pages.restaurant-info.title', [], 'en').'</title>', false);
});

it('redirects the retired delivery-zones page to restaurant-info', function () {
    // الصفحة القديمة أُدمج محتواها في /restaurant-info، ويجب أن تُحوَّل تحويلاً دائماً 301.
    $this->get('/nl/delivery-zones')
        ->assertStatus(301)
        ->assertRedirect(route('restaurant-info', ['locale' => 'nl']));

    $this->get('/en/delivery-zones')
        ->assertStatus(301)
        ->assertRedirect(route('restaurant-info', ['locale' => 'en']));
});

it('links the language switcher directly to each locale url', function () {
    $this->get('/nl')
        ->assertOk()
        ->assertSee('href="'.route('home', ['locale' => 'en']).'" hreflang="en" lang="en"', false)
        ->assertSee('href="'.route('home', ['locale' => 'ar']).'" hreflang="ar" lang="ar"', false);
});

it('exposes sitemap.xml for every locale with hreflang alternates', function () {
    $this->get('/sitemap.xml')
        ->assertOk()
        ->assertSee('<?xml version="1.0" encoding="UTF-8"?>', false)
        ->assertSee('xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"', false)
        ->assertSee('<loc>'.route('home', ['locale' => 'nl']).'</loc>', false)
        ->assertSee('<loc>'.route('home', ['locale' => 'en']).'</loc>', false)
        ->assertSee('<loc>'.route('restaurant-info', ['locale' => 'ar']).'</loc>', false)
        // الصفحة القديمة /delivery-zones يجب ألّا تظهر في خريطة الموقع لأنها تُحوَّل 301.
        ->assertDontSee('<loc>'.route('delivery-zones', ['locale' => 'ar']).'</loc>', false)
        ->assertSee('hreflang="x-default"', false);
});

it('serves robots.txt with the sitemap url and blocks the admin panel', function () {
    $this->get('/robots.txt')
        ->assertOk()
        ->assertSee('User-agent: *', false)
        ->assertSee('Disallow: /admin', false)
        ->assertSee('Sitemap: '.url('sitemap.xml'), false);
});

it('keeps the restaurant info page reachable under the locale prefix', function () {
    $this->get('/nl/restaurant-info')->assertOk();
});
