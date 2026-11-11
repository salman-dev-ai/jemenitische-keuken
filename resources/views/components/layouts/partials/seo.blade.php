{{--
    ═══════════════════════════════════════════════════════════════════════════
    📄 وسوم السيو (SEO) المركزية
    ═══════════════════════════════════════════════════════════════════════════
    تولّد كل وسوم الرأس الخاصة بمحركات البحث من مصدر واحد (App\Support\SeoMetadata):
      - الوصف (meta description) والرابط الأساسي (canonical).
      - روابط اللغات البديلة (hreflang) + x-default.
      - وسوم Open Graph و Twitter Card للمشاركة الاجتماعية.
      - البيانات المنظّمة JSON-LD (Restaurant) لظهور غني في نتائج البحث.
      - رمز تحقّق Google Search Console (إن أُدخل من لوحة التحكم).

    ⚠️ يُستدعى من ملف الرأس head بعد إنشاء المتغيرين $settings و $seo هناك.
    ═══════════════════════════════════════════════════════════════════════════
--}}

<meta name="description" content="{{ $seo->description() }}">
<link rel="canonical" href="{{ $seo->canonical() }}">

{{-- روابط اللغات البديلة (hreflang) لربط نسخ الموقع بلغاتها المختلفة --}}
@foreach ($seo->alternates() as $hreflang => $href)
    <link rel="alternate" hreflang="{{ $hreflang }}" href="{{ $href }}">
@endforeach

{{-- Open Graph: يحدّد شكل الرابط عند مشاركته في واتساب وفيسبوك ولينكدإن --}}
<meta property="og:type" content="{{ $seo->ogType() }}">
<meta property="og:site_name" content="{{ $seo->siteName() }}">
<meta property="og:title" content="{{ $seo->title() }}">
<meta property="og:description" content="{{ $seo->description() }}">
<meta property="og:url" content="{{ $seo->canonical() }}">
<meta property="og:image" content="{{ $seo->ogImage() }}">
<meta property="og:locale" content="{{ $seo->ogLocale() }}">
@foreach ($seo->alternateOgLocales() as $alternateOgLocale)
    <meta property="og:locale:alternate" content="{{ $alternateOgLocale }}">
@endforeach

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seo->title() }}">
<meta name="twitter:description" content="{{ $seo->description() }}">
<meta name="twitter:image" content="{{ $seo->ogImage() }}">
@if ($seo->twitterHandle())
    <meta name="twitter:site" content="{{ $seo->twitterHandle() }}">
@endif

{{-- رمز تحقّق Google Search Console (يُدار من لوحة التحكم) --}}
@if (filled($settings->google_site_verification))
    <meta name="google-site-verification" content="{{ $settings->google_site_verification }}">
@endif

{{-- البيانات المنظّمة JSON-LD: نوع Restaurant لتعزيز الظهور في نتائج البحث المحلي --}}
<script type="application/ld+json">{!! json_encode($seo->schema(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
