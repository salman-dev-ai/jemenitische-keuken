# المطبخ اليمني — Jemenitische Keuken

> منصة ويب متكاملة وجاهزة للإنتاج لمطعم يمني في هولندا: قائمة طعام رقمية، سلة طلبات، حجز طاولات، كوبونات وعروض، معرض صور، مناطق توصيل، موافقة كوكيز متوافقة مع GDPR، وسيو كامل — إضافةً إلى لوحة تحكم إدارية كاملة مبنية على Filament.

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-5-FDAE4B)](https://filamentphp.com)
[![Livewire](https://img.shields.io/badge/Livewire-4-4E56A6)](https://livewire.laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php&logoColor=white)](https://www.php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-4-38BDF8?logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Pest](https://img.shields.io/badge/Tests-Pest-8B5CF6)](https://pestphp.com)

**اللغات:** العربية · [English](README.md)

---

## جدول المحتويات

1. [نظرة عامة](#1-نظرة-عامة)
2. [الميزات الرئيسية](#2-الميزات-الرئيسية)
3. [التقنيات المستخدمة](#3-التقنيات-المستخدمة)
4. [البنية المعمارية للمشروع](#4-البنية-المعمارية-للمشروع)
5. [بنية الروابط متعددة اللغات والتوطين](#5-بنية-الروابط-متعددة-اللغات-والتوطين)
6. [تحسين محركات البحث (SEO)](#6-تحسين-محركات-البحث-seo)
7. [الموافقة على الكوكيز (GDPR)](#7-الموافقة-على-الكوكيز-gdpr)
8. [مسببات الحساسية والعلامات الغذائية](#8-مسببات-الحساسية-والعلامات-الغذائية)
9. [نظام المشروبات المجانية](#9-نظام-المشروبات-المجانية)
10. [نظام الطلبات والتسعير والضريبة](#10-نظام-الطلبات-والتسعير-والضريبة)
11. [نظام الحجوزات](#11-نظام-الحجوزات)
12. [نظام الكوبونات والعروض](#12-نظام-الكوبونات-والعروض)
13. [مناطق التوصيل](#13-مناطق-التوصيل)
14. [نماذج البيانات وقاعدة البيانات](#14-نماذج-البيانات-وقاعدة-البيانات)
15. [لوحة الإدارة (Filament)](#15-لوحة-الإدارة-filament)
16. [الواجهة العامة (Livewire)](#16-الواجهة-العامة-livewire)
17. [تطبيق الويب التقدمي (PWA)](#17-تطبيق-الويب-التقدمي-pwa)
18. [الأدوار والصلاحيات](#18-الأدوار-والصلاحيات)
19. [متطلبات التشغيل والتثبيت](#19-متطلبات-التشغيل-والتثبيت)
20. [متغيرات البيئة](#20-متغيرات-البيئة)
21. [أوامر التشغيل المفيدة](#21-أوامر-التشغيل-المفيدة)
22. [الاختبارات والتكامل المستمر](#22-الاختبارات-والتكامل-المستمر)
23. [بنية المجلدات](#23-بنية-المجلدات)
24. [خطوات النشر (Production)](#24-خطوات-النشر-production)
25. [ملاحظات تشغيلية](#25-ملاحظات-تشغيلية)

---

## 1) نظرة عامة

**المطبخ اليمني (Jemenitische Keuken)** تطبيق ويب أحادي الفرع (Single-Branch) لمطعم يمني في هولندا. يوفّر:

- **واجهة عامة تفاعلية** مبنية بـ Livewire + Volt (تحديث فوري دون إعادة تحميل الصفحة)، بتجربة متعددة اللغات بالكامل (الهولندية / الإنجليزية / العربية) مع دعم RTL.
- **نظام طلبات متكامل** (تناول في المطعم / استلام / توصيل / طلب مسبق) مع حساب ضريبة القيمة المضافة الهولندية (BTW).
- **نظام حجوزات** يمنع التعارض الزمني ويصدر رمز مرجعي آمن.
- **نظام كوبونات وعروض** متقدّم بحدود استخدام ونطاقات (منتج / تصنيف / الكل).
- **لوحة إدارة Filament** بمخططات (Schemas) مفصولة، وويدجت إحصائية ورسوم بيانية.
- **سيو بمستوى إنتاجي**: روابط مرتبطة باللغة، ووسوم `hreflang`، وخريطة موقع، وبيانات منظّمة JSON-LD، وتخصيص الميتا لكل لغة.
- **موافقة كوكيز متوافقة مع GDPR** بتحقق خادمي وتسجيل للموافقة.
- **دعم PWA** لتمكين تثبيت الموقع كتطبيق على شاشة الجوال الرئيسية.

---

## 2) الميزات الرئيسية

### 🌐 الواجهة العامة (للعملاء)

| الميزة | الوصف |
| --- | --- |
| الصفحة الرئيسية | شريط بطل (Hero Slider) متحرك، قسم خيارات الطلب، أطباق مميزة، وتذييل غني بمعلومات التواصل وساعات العمل |
| القائمة والسلة | عرض الأطباق حسب التصنيفات، إضافة للسلة، تعديل الكميات، وملخّص طلب مباشر |
| إتمام الطلب | اختيار نوع الطلب (تناول في المطعم / استلام / توصيل / طلب مسبق)، بيانات العميل، ودفع اختياري |
| عروض المشروبات المجانية | الأطباق المؤهلة يمكن أن تتضمن مشروبات مجانية يختارها العميل (بتحقق خادمي) |
| الحجز | نموذج حجز مع تحقق، تطبيع الأرقام العربية، منع تعارض الفترات، وإرسال تفاصيل الحجز للمدير عبر واتساب |
| الكوبونات | تطبيق/إزالة الكوبون داخل السلة، و«كوبوناتي» لحفظ الكوبونات في حساب العميل |
| العروض | قسم عروض وبروموشنات مع بطاقات مميزة وإمكانية تطبيق العرض مباشرة على السلة |
| مناطق التوصيل | مناطق وأسعار التوصيل، وتُعرض الآن داخل صفحة معلومات المطعم |
| معلومات المطعم | `/restaurant-info`: أوقات العمل، معلومات مسببات الحساسية، وجدول التوصيل |
| معرض الصور | معرض بفئات وتصفية وصور مميزة (Featured) |
| آراء العملاء | شريط تقييمات متحرك (Marquee) |
| تواصل معنا | نموذج تواصل يُخزَّن في قاعدة البيانات |
| «تذكرني» | حفظ بيانات العميل في كوكي آمنة للطرف الأول (90 يوماً) لتعبئتها تلقائياً في الزيارات التالية |
| تبديل اللغة | تبديل فوري بين الهولندية / الإنجليزية / العربية؛ ولكل لغة بادئة رابط خاصة بها |

### 🎛️ لوحة الإدارة (للفريق)

| الميزة | الوصف |
| --- | --- |
| لوحة تحكم | ويدجت حالة المطعم + رسوم بيانية للمبيعات وحالات وأنواع الطلبات |
| إدارة القائمة | تصنيفات وأطباق قابلة للترجمة، ترتيب بالسحب والإفلات، رفع صور، إعداد المشروبات المجانية |
| قاموس الحساسية | إدارة مسببات الحساسية (EU-14) والعلامات الغذائية، وربطها بالأطباق (متعدد-لمتعدد)، مع أيقونات Lucide |
| إدارة الطلبات | متابعة الحالات، تعديل الأسعار، حساب الضريبة تلقائياً، لقطات المشروبات المجانية |
| إدارة الحجوزات | الحالة (معلّق / مؤكد / جالس / ملغى / لم يحضر)، ربط بالعميل والطاولة |
| إدارة العملاء | سجل العملاء وعلاقاتهم بالطلبات والحجوزات |
| الكوبونات والعروض | حدود الاستخدام، النطاق، مدة الصلاحية، والمنتجات/التصنيفات المرتبطة |
| المحتوى والوسائط | السلايدر، معرض الصور، خيارات الطلب |
| إعدادات المطعم | سجل وحيد (Singleton) لبيانات التواصل وساعات العمل والضريبة وإعدادات الطلبات وحقول السيو |
| موافقات الكوكيز | سجل موافقات GDPR للقراءة فقط (خيارات التحليلات والتسويق) |
| المستخدمون والصلاحيات | إدارة المستخدمين مع نظام أدوار وصلاحيات (Spatie Permission) |

---

## 3) التقنيات المستخدمة

| الطبقة | التقنية | الإصدار |
| --- | --- | --- |
| الإطار الأساسي | Laravel | 12 |
| لغة البرمجة | PHP | 8.2+ |
| مكوّنات الواجهة | Livewire | 4 |
| مكوّنات ملف واحد | Livewire Volt | 1 |
| مكوّنات واجهة جاهزة | Livewire Flux (Free) | 2 |
| لوحة الإدارة | Filament | 5 |
| التنسيقات | Tailwind CSS | 4 |
| الأيقونات | mallardduck/blade-lucide-icons | 2 |
| التوطين | spatie/laravel-translatable + lara-zeus/spatie-translatable | 6 / — |
| الصلاحيات | spatie/laravel-permission | 6 |
| التجميع (Bundler) | Vite | 6 |
| الاختبارات | Pest | 3 |
| التنسيق الآلي للكود | Laravel Pint | 1 |

---

## 4) البنية المعمارية للمشروع

يعتمد المشروع فصلًا واضحًا للمسؤوليات:

```
┌─────────────────────────────────────────────────────────────┐
│                    الواجهة العامة (Public)                    │
│   Livewire Components  →  Blade Views  →  Tailwind/Alpine    │
└───────────────────────────┬─────────────────────────────────┘
                            │
┌───────────────────────────▼─────────────────────────────────┐
│               طبقة الخدمات (Service Layer)                    │
│   OrderService · CouponService · PricingService               │
│   ReservationService                                          │
└───────────────────────────┬─────────────────────────────────┘
                            │
┌───────────────────────────▼─────────────────────────────────┐
│                طبقة الدعم (Support Layer)                     │
│   SeoMetadata · OpeningHoursPresenter · GoogleMapsLink        │
└───────────────────────────┬─────────────────────────────────┘
                            │
┌───────────────────────────▼─────────────────────────────────┐
│              النماذج والمنطق (Models + Enums)                 │
│   Order · OrderItem · Reservation · Coupon · Customer ...     │
│   Enums (OrderStatus, OrderType, ...) + DTOs                  │
└───────────────────────────┬─────────────────────────────────┘
                            │
┌───────────────────────────▼─────────────────────────────────┐
│          طبقة البيانات (MySQL) + Cache + Queue               │
└─────────────────────────────────────────────────────────────┘

        لوحة الإدارة (Filament) تتعامل مباشرة مع النماذج
        والخدمات لعرض نفس الأرقام والمنطق.
```

مبادئ معتمدة في التصميم:

- **KISS / YAGNI:** بنية أحادية الفرع دون تعقيدات علائقية غير ضرورية.
- **التحقق النوعي الصارم:** استخدام `declare(strict_types=1)` وتعريف أنواع الإرجاع والوسائط.
- **فصل المخططات:** نماذج Filament مفصولة في `Schemas/` و`Tables/` لإبقاء الـ Resources نحيفة.
- **سلامة البيانات المالية:** حفظ «لقطة» (Snapshot) لسعر الوحدة وقيمة الكوبون وقت الطلب.
- **الحماية من Race Conditions:** معاملات `DB::transaction` مع `lockForUpdate` في عمليات الكوبون والطلب.
- **التحقق الخادمي:** كل قواعد العمل (الكوبونات، المشروبات المجانية، الحجوزات، موافقة الكوكيز) يُعاد التحقق منها في الخادم؛ لا تُوثق الواجهة أبداً.

### الطبقات البرمجية في `app/`

| المجلد | الدور |
| --- | --- |
| `DTOs/` | كائنات نقل بيانات (`CouponResult`, `PricingBreakdown`) |
| `Enums/` | قوائم تعداد مدعومة بـ PHP 8 تدعم `HasLabel` و`HasColor` لـ Filament |
| `Exceptions/` | استثناءات النطاق (`CouponException`, `OrderException`, `ReservationException`) |
| `Filament/` | موارد وويدجت لوحة الإدارة |
| `Http/` | متحكمات (اللغة، خريطة الموقع، robots، موافقة الكوكيز) والـ Middleware (`SetLocale`, `ShareCookieConsent`) |
| `Livewire/` | مكوّنات الواجهة العامة + Traits في `Concerns/` |
| `Models/` | نماذج Eloquent |
| `Services/` | منطق الأعمال (الطلبات، التسعير، الكوبونات، الحجوزات) |
| `Support/` | أدوات مساعدة (`SeoMetadata`, `OpeningHoursPresenter`, `GoogleMapsLink`) |

---

## 5) بنية الروابط متعددة اللغات والتوطين

يقدّم الموقع **ثلاث لغات**، لكل منها بادئة رابط خاصة حتى تفهرس محركات البحث نسخة مستقلة ومُعنونة صحيحاً لكل لغة.

| اللغة | بادئة الرابط | ملاحظات |
| --- | --- | --- |
| الهولندية (`nl`) | `/nl` | **اللغة الافتراضية** (لغة السوق المحلي في هولندا) |
| الإنجليزية (`en`) | `/en` | |
| العربية (`ar`) | `/ar` | تخطيط RTL |

**المصدر الوحيد للحقيقة:** [`config/locales.php`](file:///d:/YearTherPartOne/learn-php/projects/jemenitische-keuken-mysql/jemenitische-keuken/config/locales.php) يعرّف `supported` (`['nl','en','ar']`) و`default` (`nl`) وأسماء العرض `labels` وقيم `og_locale`. ويُعاد استخدامه في المسارات ووسوم hreflang و`sitemap.xml` ومحوّل اللغة.

**سلوك الروابط:**

- الجذر `/` يُصدر **تحويلاً دائماً (301)** إلى `/{default}` (أي `/nl`) لمنع المحتوى المكرر.
- كل صفحة تعيش ضمن بادئة لغة: `/{locale}/` و`/{locale}/restaurant-info`.
- المسار القديم `/delivery-zones` يُحوَّل **تحويلاً دائماً (301)** إلى `/{locale}/restaurant-info` للحفاظ على الروابط المؤرشفة (Backlinks).
- تُولّد `route('home')` الرابط المترجم الصحيح تلقائياً لأن `SetLocale` يضبط `URL::defaults(['locale' => ...])`.

**ترتيب تحديد اللغة** (`App\Http\Middleware\SetLocale`):

1. **بادئة الرابط** (`{locale}`) — الأولوية القصوى، وهي المصدر الصديق للسيو.
2. **الجلسة** — اختيار الزائر السابق.
3. **لغة المتصفح المفضّلة** (`Accept-Language`).
4. **اللغة الافتراضية** (`nl`).

أي قيمة غير مدعومة ترجع للغة الافتراضية. وتُحفظ اللغة بالجلسة لتبقى ثابتة في طلبات Livewire (التي لا تحمل بادئة).

**ملفات الترجمة:** `lang/{ar,en,nl}` وتشمل `messages`, `validation`, `passwords`, `pagination`, `auth`, `footer`, `delivery`, `cart`, `offers`, `coupons`, `enums`, `gallery`, `my-coupons`, `about`, `info`، و`seo`.

**محتوى قاعدة البيانات المترجم** (أسماء التصنيفات والأطباق ومسببات الحساسية والعروض) يُخزَّن في أعمدة JSON عبر `spatie/laravel-translatable`.

**تبديل اللغة:** `LanguageController::switch($locale)` من المسار `GET /lang/switch/{locale}` يحفظ الاختيار بالجلسة ويعيد للصفحة نفسها. (الروابط المباشرة بلغة أخرى تبقى الطريقة المفضّلة سيوياً للتبديل.)

---

## 6) تحسين محركات البحث (SEO)

يتولّى [`App\Support\SeoMetadata`](file:///d:/YearTherPartOne/learn-php/projects/jemenitische-keuken-mysql/jemenitische-keuken/app/Support/SeoMetadata.php) مركزة السيو في صنف واحد يولّد كل وسوم الرأس للصفحة:

- **العنوان والوصف** بأولوية: تجاوز لوحة التحكم (Filament) → نص الصفحة من `lang/{locale}/seo.php` → اسم الموقع.
- **الرابط الأساسي (Canonical)** للصفحة الحالية وبلغتها.
- **بدائل `hreflang`** لكل لغة مدعومة، إضافةً إلى **`x-default`** الموجّه للغة الافتراضية.
- **وسوم Open Graph** (`og:type`, `og:site_name`, `og:title`, `og:description`, `og:url`, `og:image`, `og:locale`, `og:locale:alternate`).
- **وسوم Twitter Card** (بما فيها `twitter:site` عند ضبط رابط X/Twitter).
- **البيانات المنظّمة JSON-LD** من النوع `Restaurant` (Schema.org): الاسم، الرابط، التواصل، العنوان البريدي، المطابخ، نطاق السعر، ساعات العمل، `sameAs` لوسائل التواصل، رابط الخريطة، والإحداثيات الجغرافية — ولا تُرسَل إلا القيم الحقيقية من إعدادات المطعم (دون تقييمات أو أسعار وهمية).
- **وسم تحقّق Google Search Console**.

**الملفات المولّدة:**

| المسار | المتحكم | الغرض |
| --- | --- | --- |
| `GET /sitemap.xml` | `SitemapController` | خريطة موقع لكل صفحة × كل لغة، وحمل كل `<url>` بدائل `hreflang` و`x-default`. و`lastmod` مُغفَل عمداً (لا تواريخ تعديل حقيقية). |
| `GET /robots.txt` | `RobotsController` | ملف robots ديناميكي برابط `Sitemap:` مطلق؛ يمنع `/admin` و`/livewire` و`/lang/switch`. |

**حقول السيو** (تُدار من مورد إعدادات المطعم): `meta_title`, `meta_description` (قابلة للترجمة), `og_image`, `price_range`, `serves_cuisine`, `social_links`, `latitude`, `longitude`, `google_site_verification`، إضافةً إلى معرّفات التتبّع (`google_analytics_id`, `meta_pixel_id`).

> تجاوزات لوحة التحكم لـ `meta_title` / `meta_description` تُطبَّق على الصفحة الرئيسية فقط؛ وبقية الصفحات تستخدم الترجمات في `lang/{locale}/seo.php`.

---

## 7) الموافقة على الكوكيز (GDPR)

نظام موافقة من الطرف الأول ومفروض من الخادم (متوافق مع Telecommunicatiewet / GDPR).

- **الإعداد:** [`config/cookies.php`](file:///d:/YearTherPartOne/learn-php/projects/jemenitische-keuken-mysql/jemenitische-keuken/config/cookies.php) — اسم الكوكي `cookie_consent`، المدة 180 يوماً، و`version` للسياسة (رفعها يُجبر الجميع على إعادة الموافقة)، وقوائم حذف الكوكيز.
- **التصنيفات:** `necessary` (مفعّلة دائماً)، `analytics`، `marketing`.
- **المتحكم:** [`CookieConsentController@store`](file:///d:/YearTherPartOne/learn-php/projects/jemenitische-keuken-mysql/jemenitische-keuken/app/Http/Controllers/CookieConsentController.php) (`POST /cookie-consent` بحدّ `30,1`) يجري **تحققاً خادمياً صارماً**، ويكتب كوكي طرف أول آمنة (`HttpOnly`، `SameSite=Lax`، `Secure` على HTTPS، تُشفَّر تلقائياً)، ويسجّل الموافقة.
- **إثبات الموافقة:** يُسجَّل في جدول `cookie_consents` (للقراءة فقط في لوحة التحكم). ويُخزَّن عنوان IP **مُجزَّأً (SHA-256 + مفتاح التطبيق)** بدل تخزينه خاماً لتقليل البيانات.
- **Middleware:** `ShareCookieConsent` يقرأ الكوكي المشفّرة ويشارك مصفوفة `$cookieConsent` موحّدة مع كل الواجهات — ولا تعتمد الواجهة على `localStorage` إطلاقاً.
- **بوابة السكربتات:** [`cookie-consent-scripts.blade.php`](file:///d:/YearTherPartOne/learn-php/projects/jemenitische-keuken-mysql/jemenitische-keuken/resources/views/components/layouts/partials/cookie-consent-scripts.blade.php) يحمّل Google Analytics 4 (`anonymize_ip`) وMeta Pixel **فقط بعد الموافقة**، ويضبط إعدادات Google Consent Mode، ويحذف الكوكيز المعنية عند سحب الموافقة.

---

## 8) مسببات الحساسية والعلامات الغذائية

معلومات الحساسية **قاموس واحد مُدار** وليس نصاً حراً:

- **نموذج `Allergen`** — اسم قابل للترجمة، و`type`، وأيقونة Lucide `icon` (أو صورة مرفوعة)، و`is_active`، و`sort_order`.
- **تعداد `AllergenType`** — `Allergen` (مسبب حساسية) و`Label` (علامة غذائية)، ولكل منهما لون شارة في Filament.
- **القاموس الافتراضي** (`Allergen::DEFAULT_CATALOG`) يشمل **مسببات الحساسية الأربعة عشر (EU-14)** (جلوتين، القشريات، البيض، الأسماك، الفول السوداني، الصويا، الحليب، المكسرات، الكرفس، الخردل، السمسم، الكبريتيت، الترمس، الرخويات) إضافةً إلى علامات غذائية (حلال، نباتي صرف، نباتي، خالٍ من اللاكتوز، خالٍ من الجلوتين، خالٍ من المكسرات، بدون سكر).
- **علاقة متعدد-لمتعدد** عبر الجدول الوسيط `allergen_menu_item`؛ وتُوفّر الأطباق `activeAllergens` (المفعّلة فقط، مرتّبة) لتجنّب استعلامات N+1.
- تُعرض معلومات الحساسية للعملاء في القائمة وفي صفحة `/restaurant-info`.

---

## 9) نظام المشروبات المجانية

يمكن إعداد أطباق معيّنة لتتضمن مشروبات مجانية:

- **الإعداد في `MenuItem`:** `offers_free_drink`, `free_drink_qty`, `free_drink_policy`, `free_drink_item_ids`.
- **السياسات:**
  - `per_unit` (الافتراضية) — يتناسب عدد المشروبات المجانية مع الكمية: `العدد × الكمية`.
  - `per_line` — عدد ثابت من المشروبات المجانية مهما كانت الكمية.
- **المشروبات المؤهلة** يجب أن تنتمي إلى تصنيف معلَّم بـ `is_drinks_category` (انظر `MenuCategory::scopeDrinks()`) ليعرف النظام دائماً الأصناف التي يمكن منحها.
- **التحقق الخادمي:** يعيد `OrderService` التحقق من كل اختيار (`buildFreeDrinkOptions`, `freeDrinkSlotsFor`) ويرمي `OrderException::freeDrinkNotEligible` / `freeDrinkQuantityExceeded` عند خرق القواعد. وتُخزَّن المشروبات المختارة كلقطة (Snapshot) بسعر `0` على سطر الطلب لتبقى الطلبات التاريخية دقيقة.
- **سلوك السلة:** إذا كان مسموحاً بمشروب واحد يُختار تلقائياً؛ وإذا تعدد يُختار من العميل مع تنبيه واضح.

---

## 10) نظام الطلبات والتسعير والضريبة

### أنواع الطلبات (`OrderType`)

`pickup` (استلام) · `dine_in` (تناول في المطعم) · `delivery` (توصيل) · `preorder` (طلب مسبق)

### حالات الطلب (`OrderStatus`)

`pending` · `processing` · `completed` · `cancelled`

### معادلة التسعير (في `PricingService`)

```
subtotal  = Σ (unit_price × quantity)
discount  = min(قيمة الكوبون , subtotal)
taxable   = subtotal − discount
vat       = taxable × vat_rate
total     = taxable + vat
```

- تُحسب الضريبة عبر `PricingService` **فقط** (لا تكرار في الواجهة أو الـ Resources).
- نسبة ضريبة المطعم تُقرأ من `RestaurantSetting.vat_rate` (كنسبة مئوية، افتراضياً `9.00` أي 9% — BTW الهولندية للمطاعم).
- تدفق إنشاء الطلب (`OrderService::createOrder`) يتم داخل معاملة واحدة (`DB::transaction`) مع `lockForUpdate` على الأطباق لمنع تغيير الأسعار أثناء التنفيذ؛ وتُنفَّذ عمليات الكوبون (claim + redeem) والتحقق من المشروبات المجانية ضمن نفس المعاملة.
- أرقام الطلبات (مثل `ORD-…`) تُولَّد تلقائياً لتجنّب كشف الترقيم التسلسلي.

---

## 11) نظام الحجوزات

- يُدار منطق الحجز عبر `ReservationService` داخل معاملة قاعدة بيانات.
- **التحقق من التعارض:** يمنع حجز فترة زمنية متداخلة مع حجز قائم في نفس اليوم، بمدة افتراضية قابلة للضبط (`RESERVATION_DURATION_MINUTES`، افتراضي 90 دقيقة)، ومدعوم بفهرس تعارض مخصص في جدول `reservations`.
- **الرموز المرجعية:** توليد رمز آمن (مثل `RES-…`) دون كشف الترقيم التسلسلي.
- **حالات الحجز (`ReservationStatus`):** `pending` · `confirmed` · `seated` · `cancelled` · `no_show`.
- **الأمان:** تحديد معدل الطلبات (Rate Limiting) بحسب عنوان IP، وتطبيع أرقام الهاتف العربية إلى أرقام لاتينية.
- **إشعار واتساب:** بعد نجاح الحجز يُبنى رابط `wa.me` يحتوي تفاصيل الحجز ويُرسل إلى رقم المطعم المسجّل في الإعدادات.

---

## 12) نظام الكوبونات والعروض

### الكوبونات

- **أنواع الخصم (`DiscountType`):** `Percentage` (نسبة مئوية) · `Fixed` (مبلغ ثابت)، مع دالة `apply()` لحساب القيمة.
- **نطاق السريان (`CouponScope`):** `All` (كل الطلب) · `Products` (منتجات محددة) · `Categories` (تصنيفات محددة).
- **حالات الاستخدام (`CouponUsageStatus`):** `Claimed` · `Used` · `Expired` · `Cancelled`.
- **شروط قابلة للضبط:** الحد الأدنى للطلب، الحد الكلي للاستخدام، الحد لكل عميل، تاريخ الانتهاء، وكوبون ترحيبي (`is_welcome`).
- **ضمانات التزامن:** جدول `coupon_usages` يمنع الاستخدام المزدوج، وتُستخدم أقفال `lockForUpdate` مع زيادات ذرّية لعدّاد الاستخدام.

### العروض (`Promotion`)

- ترتبط بعروض/كوبونات مع دعم صورة أو فيديو، وفترة بداية/نهاية، وتمييز العرض.
- حالات العرض (`MediaType`): `Image` · `Video`.

`CouponService` مسؤول عن: التحقق (`validate`)، الحساب (`calculateDiscount`)، الحفظ في حساب العميل (`claim`)، التعليق كمستخدم (`redeem`)، والتحرير عند الإلغاء (`release`).

---

## 13) مناطق التوصيل

- **نموذج `DeliveryZone`** — `region_name`, `postal_code`, `delivery_cost`, `minimum_order` (يُرجَعان دائماً بمنزلتين عشريتين).
- تُعرض مناطق وأسعار التوصيل للعملاء داخل صفحة **معلومات المطعم** (`/restaurant-info`).
- المسار القديم `/delivery-zones` يُحوَّل **301** إلى `/restaurant-info` للحفاظ على الروابط المؤرشفة.

---

## 14) نماذج البيانات وقاعدة البيانات

### 14.1 الجداول الرئيسية

- **النظام:** `users`, `cache`, `jobs`
- **الإعدادات:** `restaurant_settings`
- **القائمة:** `menu_categories`, `menu_items`
- **الحساسية:** `allergens`, `allergen_menu_item`
- **الطلبات:** `orders`, `order_items`, `order_options`
- **الحجوزات:** `reservations`
- **العملاء:** `customers`
- **الكوبونات:** `coupons`, `coupon_usages`, `coupon_product`, `coupon_category`
- **العروض والتسويق:** `promotions`, `sliders`, `gallery_items`
- **التواصل:** `contact_messages`
- **الخصوصية:** `cookie_consents`
- **التوصيل:** `delivery_zones`
- **الصلاحيات:** جداول `spatie/laravel-permission`

### 14.2 أهم النماذج (Models)

| النموذج | الوصف والعلاقات البارزة |
| --- | --- |
| `RestaurantSetting` | سجل وحيد (Singleton) مع Cache تلقائي؛ يوفّر `current()` و`vatRate()` و`maxQuantityPerItem()`؛ يحمل بيانات التواصل وساعات العمل والضريبة وإعدادات الطلبات وحقول السيو ومعرّفات التتبّع |
| `MenuCategory` | تصنيفات قابلة للترجمة؛ علاقة `menuItems` (HasMany)؛ علم `is_drinks_category` مع `scopeDrinks()` |
| `MenuItem` | أطباق بأسعار، مسببات حساسية، تمييز، حالة توفر، وإعداد المشروبات المجانية؛ scopes: `available()`, `featured()`؛ علاقات: `category`, `activeAllergens` |
| `Allergen` | قاموس الحساسية/العلامات؛ `menuItems` (BelongsToMany)؛ `isDietaryLabel()`, `iconName()` |
| `Order` | السجل المالي للطلب؛ علاقات `items`, `reservation`, `customer`, `coupon`؛ يحفظ snapshot الكوبون |
| `OrderItem` | عناصر الطلب مع حفظ `unit_price` التاريخي ولقطة المشروبات المجانية |
| `OrderOption` | خيارات الطلب (صورة، عنوان، أيقونة، حالة التفعيل) |
| `Reservation` | الحجز مع رمز مرجعي آمن؛ scopes: `forDate()`, `pending()` |
| `Customer` | بيانات العميل؛ علاقات `reservations`, `orders`, `couponUsages`؛ scopes: `new()`, `returning()` |
| `Coupon` | الكوبون بشروط الاستخدام والنطاق؛ علاقات `usages`, `menuItems`, `menuCategories`, `promotion` |
| `CouponUsage` | تتبّع استخدام العميل للكوبون بحالات (Claimed/Used/Expired/Cancelled) |
| `Promotion` | العروض المرتبطة بالكوبونات مع وسائط (صورة/فيديو) وفترات صلاحية |
| `Slider` | شرائح الصفحة الرئيسية |
| `GalleryItem` | عناصر المعرض بفئات وتمييز وعدّاد مشاهدات |
| `DeliveryZone` | مناطق التوصيل وتكلفتها والحد الأدنى للطلب |
| `ContactMessage` | رسائل نموذج التواصل |
| `CookieConsent` | سجل موافقات GDPR (uuid، التصنيفات، النسخة، IP مجزّأ، User Agent) |
| `User` | مستخدم النظام؛ يتحكم بالوصول إلى لوحة Filament |

### 14.3 القرارات المعمارية البارزة

- **توليد الرموز المرجعية تلقائيًا** (مثل `RES-…` و`ORD-…`) عبر أحداث Eloquent (`booted`) لمنع ثغرات IDOR.
- **سلامة السجل المالي:** تخزين لقطة سعر الوحدة في `order_items` وسعر/نوع الكوبون في `orders`.
- **التحويلات الحديثة:** استخدام دالة `casts()` بدل الخاصية القديمة `$casts`.
- **الترجمة الأصلية:** تخزين الحقول المترجمة داخل أعمدة JSON عبر `spatie/laravel-translatable`.
- **حماية توافق قاعدة البيانات:** الترحيلات التي تستخدم صيغة خاصة بـ MySQL تتضمن فحص مشغّل قاعدة البيانات (DB Driver check).

---

## 15) لوحة الإدارة (Filament)

يُعرَّف الإعداد بالكامل في `AdminPanelProvider`، وتشمل أهم خصائصها:

- **الرابط:** `/admin` مع صفحة تسجيل دخول مدمجة.
- **الهوية البصرية:** اللون الأساسي `#D47716` (ذهبي/برتقالي)، والبني الداكن `#3E1F15`، وخط **Tajawal**، وتنسيق مخصص للشعار، ودعم RTL.
- **اكتشاف تلقائي** للموارد والصفحات والويدجت.
- **إضافة الترجمة** `SpatieTranslatablePlugin` بلغات (إنجليزي / عربي / هولندي).
- **قفل السجل الفردي** في `RestaurantSettingResource` (تعطيل الإنشاء والحذف).
- **تفاصيل UX:** قائمة جانبية قابلة للطي، عرض محتوى كامل، تنبيه التغييرات غير المحفوظة، واختصار بحث `Ctrl/Cmd + K`.

### الموارد (Resources)

`Allergens` · `CookieConsents` · `Coupons` · `Customers` · `DeliveryZones` · `GalleryItems` · `MenuCategories` · `MenuItems` · `OrderOptions` · `Orders` · `Promotions` · `Reservations` · `RestaurantSettings` · `Sliders` · `Users`

كل مورد مقسّم إلى:

```
Resources/<Name>/
├── <Name>Resource.php        # التعريف العام
├── Pages/                    # List / Create / Edit
├── Schemas/                  # مخططات النماذج (Forms)
└── Tables/                   # تعريف الجداول
```

### الويدجت (Widgets)

| الويدجت | الوظيفة |
| --- | --- |
| `RestaurantStatus` | إحصائيات سريعة: حالة الطلبات الإلكترونية، حالة الحجوزات، وإجمالي الإيرادات |
| `RestaurantSalesChart` | رسم خطي للمبيعات مع تبديل بين آخر 7 / 30 يوماً |
| `OrderStatusChart` | رسم دائري لتوزيع حالات الطلبات |
| `OrderTypesChart` | رسم شريطي لأنواع الطلبات |

---

## 16) الواجهة العامة (Livewire)

### المكوّنات

| المكوّن | الوظيفة |
| --- | --- |
| `HomePage` | الصفحة الرئيسية: شريط البطل وخيارات الطلب |
| `MenuWithCart` | المكوّن الرئيسي: القائمة + السلة + الحجز + إتمام الطلب |
| `MenuCategories` | عرض وتصفية تصنيفات القائمة |
| `PopularDishes` | قسم الأطباق المميزة |
| `OffersSection` | قسم الكوبونات/العروض مع بطاقات مميزة |
| `ReservationForm` | نموذج حجز مستقل مع إشعار واتساب للمدير |
| `GallerySection` | معرض الصور بالفئات والتصفية |
| `CustomerReviewsMarquee` | شريط آراء العملاء المتحرك |
| `Footer` | التذييل مع معلومات التواصل وساعات العمل وتبديل اللغة |
| `ShowDeliveryZones` | عرض مناطق التوصيل (يُستخدم في صفحة معلومات المطعم) |
| `ContactForm` | نموذج التواصل وحفظ الرسائل |
| `ErrorReport` | مكوّن الإبلاغ عن الأخطاء |
| `RestaurantInfo` | صفحة معلومات المطعم (`/restaurant-info`) |

### التخطيط والأجزاء (Layout & Partials)

الغلاف الأساسي `components/layouts/app.blade.php` يجمع أجزاءً صغيرة تحت `components/layouts/partials/` (`head`, `seo`, `helpers`, أجزاء الهيدر، `hero`, `footer`, `scroll-to-top`, `cookie-notice`, `cookie-consent-scripts`, `toasts`, `confirm`, `scripts`). وتوجد صفحات أخطاء مخصصة لـ `401, 403, 404, 419, 429, 500, 503`.

### توزيع منطق `MenuWithCart` عبر Traits

لإبقاء المكوّن نحيفًا، وُزّع المنطق على Traits في `app/Livewire/Concerns`:

| الـ Trait | المسؤولية |
| --- | --- |
| `ManagesCart` | إضافة/تحديث/حذف عناصر السلة، حساب المجاميع، ومعالجة اختيارات المشروبات المجانية |
| `ManagesCoupon` | تطبيق وإزالة الكوبون داخل السلة |
| `ManagesMyCoupons` | نافذة «كوبوناتي» وحفظ/نسخ الكوبونات |
| `ManagesMenu` | عرض الأطباق والتصنيفات والفلترة |
| `ValidatesCheckout` | قواعد التحقق ورسائلها وتطبيع نوع الطلب |
| `ProcessesCheckout` | إتمام الطلب وبناء بيانات الحجز/الطلب ورسالة واتساب |
| `RemembersCustomer` | كوكي «تذكرني» وحفظ/تحديث العميل وتطبيع الهاتف |

> ملاحظة معمارية: تعتمد الـ Traits على خصائص المكوّن (مثل `$cart` و`$order_type`)، لذا تُضاف أي خصائص جديدة في المكوّن نفسه لا داخل الـ Trait.

---

## 17) تطبيق الويب التقدمي (PWA)

- ملف البيان: `public/manifest.json` (اسم التطبيق، الألوان، الأيقونات، `display: standalone`، اتجاه RTL).
- الأيقونات: `public/icons/` (تشمل `icon-192`, `icon-512`, `icon-maskable-512`, `apple-touch-icon`).
- Service Worker: `public/sw.js` ويُسجَّل تلقائياً من شريط السكربتات في القالب.
- وسمات PWA في `<head>`: `manifest`، `theme-color` (`#E07513`)، و`apple-mobile-web-app-*`.

> ⚠️ ليعمل تثبيت التطبيق و Service Worker يجب تقديم الموقع عبر **HTTPS** في بيئة الإنتاج.

---

## 18) الأدوار والصلاحيات

يُهيّئ `RolesAndPermissionsSeeder` الأدوار والصلاحيات التالية:

| الدور | النطاق |
| --- | --- |
| `super_admin` | كل الصلاحيات |
| `admin` | القائمة، الطلبات، العملاء، الحجوزات، المعرض، وإعدادات المطعم |
| `content_manager` | تصنيفات وأطباق القائمة + المعرض فقط |

الصلاحيات مصنّفة حسب المورد (view / create / edit / delete) مثل: `menu_items.*`, `orders.*`, `reservations.*`, `customers.*`, `gallery.*`، إضافةً إلى `restaurant_settings.view/edit` و`users.*`.

> ⚠️ **تنبيه أمني:** يقوم الـ Seeder بإنشاء مستخدم افتراضي `admin@jemenitische-keuken.nl` بكلمة المرور الافتراضية `ChangeThisPassword123!`. يجب تغييرها فوراً بعد أول نشر، وعدم الإبقاء على بيانات الـ Seeder في الإنتاج.

---

## 19) متطلبات التشغيل والتثبيت

### المتطلبات

- PHP 8.2 أو أحدث مع الإضافات الأساسية لـ Laravel
- Composer
- Node.js 22+ و npm
- قاعدة بيانات (MySQL في الإنتاج؛ SQLite مدعومة للاختبارات)

### خطوات التثبيت

```bash
# 1) تثبيت حزم PHP
composer install

# 2) تهيئة ملف البيئة ومفتاح التطبيق
copy .env.example .env
php artisan key:generate

# 3) تثبيت حزم الواجهة وبناء الأصول
npm install
npm run build

# 4) تهيئة قاعدة البيانات مع البيانات التجريبية
php artisan migrate:fresh --seed

# 5) إنشاء رابط التخزين (لصور المنيو والوسائط)
php artisan storage:link
```

### تشغيل بيئة التطوير

```bash
# يشغّل في آن واحد: خادم Laravel + المستمع للطوابير + Vite
composer run dev
```

أو كل خدمة على حدة:

```bash
php artisan serve
npm run dev
php artisan queue:listen --tries=1 --timeout=0
```

---

## 20) متغيرات البيئة

أهم المتغيرات المتعلقة بالمشروع (إضافةً إلى إعدادات Laravel القياسية):

| المتغير | الوصف | القيمة الافتراضية |
| --- | --- | --- |
| `APP_NAME` | اسم التطبيق | `Jemenitische Keuken` |
| `APP_URL` | رابط التطبيق (مهم لروابط التخزين والصور، ولرابط خريطة الموقع المطلق) | `http://localhost` |
| `APP_LOCALE` | لغة الإطار | `en` |
| `APP_FALLBACK_LOCALE` | اللغة الاحتياطية | `en` |
| `ORDER_VAT_RATE` | نسبة الضريبة الافتراضية ككسر عشري (تُستخدم عند عدم وجود إعداد يغطيها) | `0.09` |
| `ORDER_MAX_QTY_PER_ITEM` | الحد الأقصى لكمية الطبق الواحد | `50` |
| `RESERVATION_DURATION_MINUTES` | مدة الحجز الافتراضية بالدقائق | `90` |
| `RESERVATION_COOKIE_SECURE` | إجبار كوكي «تذكرني» على HTTPS | `true` |
| `DB_*` | إعدادات قاعدة البيانات (MySQL) | — |
| `QUEUE_CONNECTION` | مشغّل الطوابير | `database` |
| `SESSION_DRIVER` / `CACHE_STORE` | مخزن الجلسة والكاش | `database` |

> **اللغة الافتراضية لروابط الموقع والسيو هي `nl`** ومعرّفة في `config/locales.php` (وليس عبر `APP_LOCALE` التي تضبط لغة الإطار فقط).

ملاحظات مهمة عند ضبط `.env`:

- لا تُضِف **مسافات** حول علامة `=` (مثل `DB_HOST = 127.0.0.1`)؛ فهي تسبب فشل الاتصال بقاعدة البيانات.
- في الإنتاج: `APP_ENV=production` و`APP_DEBUG=false`.
- تأكد من ضبط `APP_URL` الصحيح ليعمل `storage:link` وروابط الصور و`sitemap.xml` بشكل سليم.

---

## 21) أوامر التشغيل المفيدة

| الأمر | الوظيفة |
| --- | --- |
| `php artisan migrate:fresh --seed` | إعادة بناء قاعدة البيانات مع بيانات تجريبية |
| `php artisan storage:link` | ربط مجلد التخزين العام (للصور) |
| `php artisan test --compact` | تشغيل الاختبارات |
| `php artisan test --compact --filter=TestName` | تشغيل اختبار محدد |
| `vendor/bin/pint --dirty --format agent` | تنسيق ملفات PHP المعدّلة |
| `php artisan pail` | متابعة السجلات لحظيًا |
| `npm run build` / `npm run dev` | بناء/تشغيل أصول الواجهة |

---

## 22) الاختبارات والتكامل المستمر

- إطار الاختبارات: **Pest 3**، وتقع الاختبارات في `tests/Feature` و`tests/Unit`.
- اختبارات بارزة:
  - `SeoTest` — خريطة الموقع، robots، canonical، hreflang، وJSON-LD.
  - `RestaurantInfoPageTest` — صفحة `/restaurant-info` وتحويل `delivery-zones`.
  - `CookieConsentTest` — التحقق الخادمي للموافقة وتسجيلها.
  - `FreeDrinkOrderTest` — أهلية المشروبات المجانية وقواعد الكمية.
  - `AllergenMenuLinkTest` — ربط مسببات الحساسية بالأطباق.
  - `PwaInstallationTest` — التحقق من استجابة الصفحة الرئيسية.
  - `RestaurantSettingSingletonTest` — ضمان عدم تكرار سجل الإعدادات وعمل الكاش.
  - `MyCouponsFilterTest` / `CouponCopyButtonTest` — ميزات كوبونات الحساب.
  - اختبارات المصادقة والملف الشخصي والإعدادات.
- **CI:** خطوط عمل GitHub Actions في `.github/workflows`:
  - `tests.yml` — تشغيل `pest` على PHP 8.4 مع بناء الأصول.
  - `lint.yml` — فحص تنسيق الكود.

```bash
php artisan test --compact
```

---

## 23) بنية المجلدات

```
jemenitische-keuken/
├── app/
│   ├── DTOs/                 # كائنات نقل البيانات
│   ├── Enums/                # قوائم التعداد
│   ├── Exceptions/           # استثناءات النطاق
│   ├── Filament/             # موارد وويدجت لوحة الإدارة
│   ├── Http/                 # المتحكمات والـ Middleware
│   ├── Livewire/             # مكوّنات الواجهة العامة + Concerns/
│   ├── Models/               # نماذج Eloquent
│   ├── Providers/            # مزوّدات الخدمات (Filament, Volt, App)
│   ├── Services/             # منطق الأعمال
│   └── Support/              # أدوات مساعدة (SEO، ساعات العمل، الخرائط)
├── bootstrap/                # تهيئة التطبيق (app.php, providers.php)
├── config/                   # ملفات الإعداد (orders, reservations, locales, cookies, ...)
├── database/
│   ├── factories/            # مصانع البيانات
│   ├── migrations/           # مخطط قاعدة البيانات
│   └── seeders/              # البيانات الأولية
├── lang/                     # ملفات الترجمة (ar, en, nl)
├── public/                   # الأصول العامة + manifest.json + sw.js + icons/
├── resources/
│   ├── css/  js/             # أصول Vite
│   └── views/                # قوالب Blade (components, livewire, filament, errors)
├── routes/                   # web.php, console.php
├── tests/                    # Pest tests (Feature, Unit)
└── .github/workflows/        # خطوط CI (tests, lint)
```

---

## 24) خطوات النشر (Production)

1. ضبط `.env` للإنتاج: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` الصحيح، وبيانات DB و SMTP.
2. تثبيت الحزم بإنتاجية: `composer install --no-dev --optimize-autoloader`.
3. بناء الأصول: `npm ci && npm run build`.
4. تنفيذ الترحيلات: `php artisan migrate --force`.
5. إنشاء رابط التخزين: `php artisan storage:link`.
6. تحسين الأداء: `php artisan config:cache && php artisan route:cache && php artisan view:cache`.
7. تفعيل **HTTPS** — مطلوب لـ PWA و Service Worker والكوكيز الآمنة.
8. تشغيل عامل الطوابير (Queue Worker) واستمراره في الخلفية.
9. *(اختياري)* إرسال `https://your-domain/sitemap.xml` إلى Google Search Console وضبط رمز التحقّق في إعدادات المطعم.

---

## 25) ملاحظات تشغيلية

- **الأمان:** تأكد من ضبط `APP_DEBUG=false` في الإنتاج، ومن تغيير بيانات المستخدم الافتراضي الناتج عن الـ Seeder فوراً.
- **الضريبة:** راجع قيمة `vat_rate` في إعدادات المطعم (افتراضيًا 9% وفق النظام الهولندي).
- **الوقت والمنطقة الزمنية:** يُضبط من `config/app.php` — تأكد من مطابقتها لمنطقة عمل المطعم.
- **اللغات:** أضف أو أعد ترتيب اللغات من `config/locales.php` فقط؛ وتتحدّث المسارات وhreflang وخريطة الموقع تلقائياً.
- **الصور والوسائط:** تعتمد على `storage:link` و`APP_URL` الصحيح لعرض الصور بشكل سليم.
- **قاعدة البيانات:** عند تعديل أي عمود في Migration جديدة يجب تضمين كل سماته الأصلية لتجنّب فقدان الخصائص.
- **تنظيف:** يجب أن يحتوي مجلد `public/` على الأصول المستخدمة فعلياً فقط؛ احذف أي بقايا تصميم/مراجع (مثل مجلدات صفحات ويب محفوظة) قبل التسليم لأنها تُضخّم حجم البناء.

---

> لمزيد من التفاصيل التقنية عن كل طبقة، راجع ملفات الكود داخل `app/` ومخططات قاعدة البيانات في `database/migrations/`.
