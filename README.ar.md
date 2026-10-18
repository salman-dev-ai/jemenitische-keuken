# المطبخ اليمني — Jemenitische Keuken

> منصة ويب متكاملة لمطعم يمني في هولندا: قائمة طعام رقمية، سلة طلبات، حجز طاولات، كوبونات وعروض، معرض صور، ومناطق توصيل — إضافةً إلى لوحة تحكم إدارية كاملة مبنية على Filament.

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-5-FDAE4B)](https://filamentphp.com)
[![Livewire](https://img.shields.io/badge/Livewire-4-4E56A6)](https://livewire.laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php&logoColor=white)](https://www.php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-4-38BDF8?logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Pest](https://img.shields.io/badge/Tests-Pest-8B5CF6)](https://pestphp.com)

**اللغات:** العربية · [English](README.md)

---

## 📑 جدول المحتويات

1. نظرة عامة
2. الميزات الرئيسية
3. التقنيات المستخدمة
4. البنية المعمارية للمشروع
5. نماذج البيانات وقاعدة البيانات
6. لوحة الإدارة (Filament)
7. الواجهة العامة (Livewire)
8. نظام الطلبات والتسعير والضريبة
9. نظام الحجوزات
10. نظام الكوبونات والعروض
11. تعدد اللغات (التوطين)
12. تطبيق الويب التقدمي (PWA)
13. الأدوار والصلاحيات
14. متطلبات التشغيل والتثبيت
15. متغيرات البيئة
16. أوامر التشغيل المفيدة
17. الاختبارات والتكامل المستمر
18. بنية المجلدات
19. خطوات النشر (Production)
20. ملاحظات وتوصيات تشغيلية

---

## 1) نظرة عامة

**المطبخ اليمني (Jemenitische Keuken)** هو تطبيق ويب أحادي الفرع (Single-Branch) مصمم ليعمل كمطعم يمني في هولندا، بواجهة عامة عربية-أولاً (RTL) تخدم العملاء، ولوحة تحكم إدارية كاملة تخدم فريق المطعم.

يجمع المشروع بين:

- **واجهة عامة تفاعلية** تُبنى بـ Livewire + Volt (تحديث فوري دون إعادة تحميل الصفحة).
- **نظام طلبات متكامل** (استلام / تناول في المطعم / توصيل / طلب مسبق) مع حساب ضريبة القيمة المضافة الهولندية (BTW).
- **نظام حجوزات** يمنع التعارض الزمني ويصدر رمز مرجعي آمن.
- **نظام كوبونات وعروض** متقدّم بصلاحيات استخدام وحدود ونطاقات (منتج/تصنيف/الكل).
- **لوحة إدارة Filament** بمخططات (Schemas) مفصولة، وويدجت إحصائية ورسوم بيانية.
- **دعم PWA** لتمكين تثبيت الموقع كتطبيق على شاشة الجوال الرئيسية.

---

## 2) الميزات الرئيسية

### 🌐 الواجهة العامة (للعملاء)

| الميزة | الوصف |
| --- | --- |
| الصفحة الرئيسية | شريط شرائح (Slider) متحرك، قسم خيارات الطلب، ترويسة، تذييل غني بمعلومات التواصل وساعات العمل |
| القائمة والسلة | عرض الأطباق حسب التصنيفات، إضافة للسلة، تعديل الكميات، وملخّص طلب مباشر |
| إتمام الطلب | اختيار نوع الطلب (تناول في المطعم / استلام / توصيل / طلب مسبق)، بيانات العميل، ودفع اختياري |
| الحجز | نموذج حجز مع تحقق، تطبيع الأرقام العربية، منع تعارض الفترات، وإرسال تفاصيل الحجز للمدير عبر واتساب |
| الكوبونات | تطبيق/إزالة الكوبون داخل السلة، و«كوبوناتي» لحفظ الكوبونات في حساب العميل |
| العروض | قسم عروض وبروموشنات مع بطاقات مميزة وإمكانية تطبيق العرض مباشرة على السلة |
| مناطق التوصيل | صفحة تعرض مناطق وأسعار التوصيل (`/delivery-zones`) |
| معرض الصور | معرض بفئات وتصفية وصور مميزة (Featured) |
| آراء العملاء | شريط تقييمات متحرك (Marquee) |
| تواصل معنا | نموذج تواصل يُخزَّن في قاعدة البيانات |
| «تذكرني» | حفظ بيانات العميل في كوكي آمن (90 يوماً) لتعبئتها تلقائياً في الزيارات التالية |
| تبديل اللغة | تبديل فوري بين العربية / الإنجليزية / الهولندية عبر `/lang/switch/{locale}` |

### 🎛️ لوحة الإدارة (للفريق)

| الميزة | الوصف |
| --- | --- |
| لوحة تحكم | ويدجت حالة المطعم + رسوم بيانية للمبيعات وحالات وأنواع الطلبات |
| إدارة القائمة | تصنيفات وأطباق قابلة للترجمة، ترتيب بالسحب والإفلات، رفع صور، تتبع مسببات الحساسية |
| إدارة الطلبات | متابعة الحالات، تعديل الأسعار، حساب الضريبة تلقائياً |
| إدارة الحجوزات | الحالة (معلّق/مؤكد/جلس/ملغى/لم يحضر)، ربط بالعميل والطاولة |
| إدارة العملاء | سجل العملاء وعلاقاتهم بالطلبات والحجوزات |
| الكوبونات والعروض | حدود الاستخدام، النطاق، مدة الصلاحية، والمنتجات/التصنيفات المرتبطة |
| المحتوى والوسائط | السلايدر، معرض الصور، خيارات الطلب |
| إعدادات المطعم | سجل وحيد (Singleton) للعنوان وساعات العمل والضريبة وتفعيل الطلبات/الحجوزات |
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
- **سلامة البيانات المالية:** حفظ «لقطة» (Snapshot) لسعر الوحدة والكوبون وقت الطلب.
- **الحماية من Race Conditions:** معاملات `DB::transaction` مع `lockForUpdate` في عمليات الكوبون والطلب.

### الطبقات البرمجية في `app/`

| المجلد | الدور |
| --- | --- |
| `DTOs/` | كائنات نقل بيانات (`CouponResult`, `PricingBreakdown`) |
| `Enums/` | قوائم تعداد مدعومة بـ PHP 8 تدعم `HasLabel` و`HasColor` لـ Filament |
| `Exceptions/` | استثناءات النطاق (`CouponException`, `OrderException`, `ReservationException`) |
| `Filament/` | موارد وويدجت لوحة الإدارة |
| `Http/` | متحكم اللغة والـ Middleware لتحديد اللغة |
| `Livewire/` | مكوّنات الواجهة العامة + Traits في `Concerns/` |
| `Models/` | نماذج Eloquent |
| `Services/` | منطق الأعمال (الطلبات، التسعير، الكوبونات، الحجوزات) |
| `Support/` | أدوات مساعدة (`OpeningHoursPresenter`) |

---

## 5) نماذج البيانات وقاعدة البيانات

### 5.1 الجداول الرئيسية

المخطط مبني عبر الـ Migrations، ويشمل الجداول التالية:

- **النظام:** `users`, `cache`, `jobs`
- **الإعدادات:** `restaurant_settings`
- **القائمة:** `menu_categories`, `menu_items`
- **الطلبات:** `orders`, `order_items`, `order_options`
- **الحجوزات:** `reservations`
- **العملاء:** `customers`
- **الكوبونات:** `coupons`, `coupon_usages`, `coupon_product`, `coupon_category`
- **العروض والتسويق:** `promotions`, `sliders`, `gallery_items`
- **التواصل:** `contact_messages`
- **التوصيل:** `delivery_zones`
- **الصلاحيات:** جداول `spatie/laravel-permission`

### 5.2 أهم النماذج (Models)

| النموذج | الوصف والعلاقات البارزة |
| --- | --- |
| `RestaurantSetting` | سجل وحيد (Singleton) مع Cache تلقائي؛ يوفّر `current()` و`vatRate()` و`maxQuantityPerItem()` |
| `MenuCategory` | تصنيفات قابلة للترجمة؛ علاقة `menuItems` (HasMany) |
| `MenuItem` | أطباق بأسعار، مسببات حساسية، تمييز، وحالة توفر؛ scopes: `available()`, `featured()` |
| `Order` | السجل المالي للطلب؛ علاقات `items`, `reservation`, `customer`, `coupon`؛ يحفظ snapshot الكوبون |
| `OrderItem` | عناصر الطلب مع حفظ `unit_price` التاريخي |
| `OrderOption` | خيارات الطلب (صورة، عنوان، أيقونة، حالة التفعيل) |
| `Reservation` | الحجز مع رمز مرجعي آمن؛ scopes: `forDate()`, `pending()` |
| `Customer` | بيانات العميل؛ علاقات `reservations`, `orders`, `couponUsages`؛ scopes: `new()`, `returning()` |
| `Coupon` | الكوبون بشروط الاستخدام والنطاق؛ علاقات `usages`, `menuItems`, `menuCategories`, `promotion` |
| `CouponUsage` | تتبّع استخدام العميل للكوبون بحالات (Claimed/Used/Expired/Cancelled) |
| `Promotion` | العروض المرتبطة بالكوبونات مع وسائط (صورة/فيديو) وفترات صلاحية |
| `Slider` | شرائح الصفحة الرئيسية |
| `GalleryItem` | عناصر المعرض بفئات وتمييز وعدّاد مشاهدات |
| `DeliveryZone` | مناطق التوصيل وتكلفتها حسب المنطقة/الرمز البريدي |
| `ContactMessage` | رسائل نموذج التواصل |
| `User` | مستخدم النظام؛ يتحكم بالوصول إلى لوحة Filament |

### 5.3 القرارات المعمارية البارزة

- **توليد الرموز المرجعية تلقائيًا** (مثل `RES-…` و`ORD-…`) عبر أحداث Eloquent (`booted`) لمنع ثغرات IDOR.
- **سلامة السجل المالي:** تخزين لقطة سعر الوحدة في `order_items` وسعر/نوع الكوبون في `orders`.
- **التحويلات الحديثة:** استخدام دالة `casts()` بدل الخاصية القديمة `$casts`.
- **الترجمة الأصلية:** تخزين الحقول المترجمة داخل أعمدة JSON عبر `spatie/laravel-translatable`.

---

## 6) لوحة الإدارة (Filament)

يُعرَّف الإعداد بالكامل في `AdminPanelProvider`، وتشمل أهم خصائصها:

- **الرابط:** `/admin` مع صفحة تسجيل دخول مدمجة.
- **الهوية البصرية:** اللون الأساسي `#D47716` (ذهبي/برتقالي)، والبني الداكن `#3E1F15`، وخط **Tajawal** ودعم RTL.
- **اكتشاف تلقائي** للموارد والصفحات والويدجت.
- **إضافة الترجمة** `SpatieTranslatablePlugin` بلغات (عربي / إنجليزي / هولندي).
- **قفل السجل الفردي** في `RestaurantSettingResource` (تعطيل الإنشاء والحذف).
- **تفاصيل UX:** قائمة جانبية قابلة للطي، عرض محتوى كامل، تنبيه التغييرات غير المحفوظة، واختصار بحث `Ctrl/Cmd + K`.

### الموارد (Resources)

`Coupons` · `Customers` · `DeliveryZones` · `GalleryItems` · `MenuCategories` · `MenuItems` · `OrderOptions` · `Orders` · `Promotions` · `Reservations` · `RestaurantSettings` · `Sliders` · `Users`

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

## 7) الواجهة العامة (Livewire)

### المكوّنات

| المكوّن | الوظيفة |
| --- | --- |
| `HomePage` | الصفحة الرئيسية: الشرائح وخيارات الطلب |
| `MenuWithCart` | المكوّن الرئيسي: القائمة + السلة + الحجز + إتمام الطلب |
| `MenuCategories` | عرض وتصفية تصنيفات القائمة |
| `ReservationForm` | نموذج حجز مستقل مع إشعار واتساب للمدير |
| `GallerySection` | معرض الصور بالفئات والتصفية |
| `CustomerReviewsMarquee` | شريط آراء العملاء المتحرك |
| `Footer` | التذييل مع معلومات التواصل وساعات العمل وتبديل اللغة |
| `ShowDeliveryZones` | عرض مناطق التوصيل |
| `ContactForm` | نموذج التواصل وحفظ الرسائل |

### توزيع منطق `MenuWithCart` عبر Traits

لإبقاء المكوّن نحيفًا، وُزّع المنطق على Traits في `app/Livewire/Concerns`:

| الـ Trait | المسؤولية |
| --- | --- |
| `ManagesCart` | إضافة/تحديث/حذف عناصر السلة وحساب المجاميع |
| `ManagesCoupon` | تطبيق وإزالة الكوبون داخل السلة |
| `ManagesMyCoupons` | نافذة «كوبوناتي» وحفظ/نسخ الكوبونات |
| `ManagesMenu` | عرض الأطباق والتصنيفات والفلترة |
| `ValidatesCheckout` | قواعد التحقق ورسائلها وتطبيع نوع الطلب |
| `ProcessesCheckout` | إتمام الطلب وبناء بيانات الحجز/الطلب ورسالة واتساب |
| `RemembersCustomer` | كوكي «تذكرني» وحفظ/تحديث العميل وتطبيع الهاتف |

> ملاحظة معمارية: تعتمد الـ Traits على خصائص المكوّن (مثل `$cart` و`$order_type`)، لذا تُضاف أي خصائص جديدة في المكوّن نفسه لا داخل الـ Trait.

---

## 8) نظام الطلبات والتسعير والضريبة

### أنواع الطلبات (`OrderType`)

`pickup` (استلام) · `dine_in` (تناول في المطعم) · `delivery` (توصيل) · `preorder` (طلب مسبق)

### حالات الطلب (`OrderStatus`)

`pending` · `processing` · `completed` · `cancelled`

### معادلة التسعير (في `PricingService`)

```
subtotal  = Σ (unit_price × quantity)
discount  = min(قيمة الكوبون , subtotal)
taxable   = subtotal − discount
vat       = taxable × (vat_rate ÷ 100)
total     = taxable + vat
```

- تُحسب الضريبة عبر `PricingService` فقط (لا تكرار في الواجهة أو الـ Resources).
- نسبة ضريبة المطعم تُقرأ من `RestaurantSetting.vat_rate` (افتراضيًا `9.00` أي 9% — BTW الهولندية للمطاعم).
- تدفق إنشاء الطلب (`OrderService::createOrder`) يتم داخل معاملة واحدة (`DB::transaction`) مع `lockForUpdate` على الأطباق لمنع تغيير الأسعار أثناء التنفيذ، وتُنفَّذ عمليات الكوبون (claim + redeem) ضمن نفس المعاملة.

---

## 9) نظام الحجوزات

- يُدار منطق الحجز عبر `ReservationService` داخل معاملة قاعدة بيانات.
- **التحقق من التعارض:** يمنع حجز فترة زمنية متداخلة مع حجز قائم في نفس اليوم، بمدة افتراضية قابلة للضبط (`RESERVATION_DURATION_MINUTES`, افتراضي 90 دقيقة).
- **الرموز المرجعية:** توليد رمز آمن (مثل `RES-…`) بدون كشف الترقيم التسلسلي.
- **حالات الحجز (`ReservationStatus`):** `pending` · `confirmed` · `seated` · `cancelled` · `no_show`.
- **الأمان:** تحديد معدل الطلبات (Rate Limiting) بحسب عنوان IP، وتطبيع أرقام الهاتف العربية إلى أرقام لاتينية.
- **إشعار واتساب:** بعد نجاح الحجز يُبنى رابط `wa.me` يحتوي تفاصيل الحجز ويُرسل إلى رقم المطعم المسجّل في الإعدادات.

---

## 10) نظام الكوبونات والعروض

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

## 11) تعدد اللغات (التوطين)

يدعم المشروع ثلاث لغات: **العربية** (الافتراضية) والإنجليزية والهولندية.

- ملفات الترجمة موزّعة في `lang/ar`, `lang/en`, `lang/nl` وتشمل: `messages`, `validation`, `passwords`, `pagination`, `auth`, `footer`, `delivery`, `cart`, `offers`, `coupons`, `enums`, `gallery`, `my-coupons`.
- يُحدَّد اللغة عبر الميدل وير `SetLocale` بالترتيب: **الجلسة → تفضيل المتصفح → اللغة الافتراضية**.
- التبديل الفوري عبر `LanguageController::switch($locale)` من المسار `lang/switch/{locale}`.
- محتوى قاعدة البيانات المترجم (أسماء التصنيفات والأطباق والعروض) يُخزَّن في أعمدة JSON عبر `spatie/laravel-translatable`.

---

## 12) تطبيق الويب التقدمي (PWA)

- ملف البيان: `public/manifest.json` (اسم التطبيق، الألوان، الأيقونات، `display: standalone`، اتجاه RTL).
- الأيقونات: `public/icons/` (تشمل `icon-192`, `icon-512`, `icon-maskable-512`, `apple-touch-icon`).
- Service Worker: `public/sw.js` ويُسجَّل تلقائيًا من شريط السكربتات في القالب.
- وسمات PWA في `<head>`: `manifest`، `theme-color`، `apple-mobile-web-app-*`.

> ⚠️ ليعمل تثبيت التطبيق و Service Worker يجب تقديم الموقع عبر **HTTPS** في بيئة الإنتاج.

---

## 13) الأدوار والصلاحيات

يُهيّئ `RolesAndPermissionsSeeder` الأدوار والصلاحيات التالية:

| الدور | النطاق |
| --- | --- |
| `super_admin` | كل الصلاحيات |
| `admin` | القائمة، الطلبات، العملاء، الحجوزات، المعرض، وإعدادات المطعم |
| `content_manager` | تصنيفات وأطباق القائمة + المعرض فقط |

الصلاحيات مصنّفة حسب المورد (view / create / edit / delete) مثل: `menu_items.*`, `orders.*`, `reservations.*`, `customers.*`, `gallery.*`, إضافةً إلى `restaurant_settings.view/edit` و`users.*`.

> ⚠️ **تنبيه أمني:** يقوم الـ Seeder بإنشاء مستخدم افتراضي `admin@jemenitische-keuken.nl` بكلمة مرور افتراضية. يجب تغيير كلمة المرور فورًا بعد أول نشر، وعدم استخدام بيانات الـ Seeder في الإنتاج.

---

## 14) متطلبات التشغيل والتثبيت

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

## 15) متغيرات البيئة

أهم المتغيرات المتعلقة بالمشروع (إضافةً إلى إعدادات Laravel القياسية):

| المتغير | الوصف | القيمة الافتراضية |
| --- | --- | --- |
| `APP_NAME` | اسم التطبيق | `Jemenitische Keuken` |
| `APP_URL` | رابط التطبيق (مهم لروابط التخزين والصور) | `http://localhost` |
| `APP_LOCALE` | اللغة الافتراضية | `ar` |
| `APP_FALLBACK_LOCALE` | اللغة الاحتياطية | `en` |
| `ORDER_VAT_RATE` | نسبة ضريبة القيمة المضافة ككسر عشري | `0.09` |
| `ORDER_MAX_QTY_PER_ITEM` | الحد الأقصى لكمية الطبق الواحد | `50` |
| `RESERVATION_DURATION_MINUTES` | مدة الحجز الافتراضية بالدقائق | `90` |
| `RESERVATION_COOKIE_SECURE` | إجبار كوكي «تذكرني» على HTTPS | `true` |
| `DB_*` | إعدادات قاعدة البيانات (MySQL) | — |
| `QUEUE_CONNECTION` | مشغّل الطوابير | `database` |
| `SESSION_DRIVER` / `CACHE_STORE` | مخزن الجلسة والكاش | `database` |

ملاحظات مهمة عند ضبط `.env`:

- لا تُضِف **مسافات** حول علامة `=` (مثل `DB_HOST = 127.0.0.1`)؛ فهي تسبب فشل الاتصال بقاعدة البيانات.
- في الإنتاج: `APP_ENV=production` و`APP_DEBUG=false`.
- تأكد من ضبط `APP_URL` الصحيح ليعمل `storage:link` وروابط الصور بشكل سليم.

---

## 16) أوامر التشغيل المفيدة

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

## 17) الاختبارات والتكامل المستمر

- إطار الاختبارات: **Pest 3**، وتقع الاختبارات في `tests/Feature` و`tests/Unit`.
- اختبارات بارزة:
  - `PwaInstallationTest` — التحقق من استجابة الصفحة الرئيسية.
  - `RestaurantSettingSingletonTest` — ضمان عدم تكرار سجل الإعدادات وعمل الكاش.
  - اختبارات المصادقة والملف الشخصي والكوبونات.
- **CI:** خطوط عمل GitHub Actions في `.github/workflows`:
  - `tests.yml` — تشغيل `pest` على PHP 8.4 مع بناء الأصول.
  - `lint.yml` — فحص تنسيق الكود.

```bash
php artisan test --compact
```

---

## 18) بنية المجلدات

```
jemenitische-keuken-chat/
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
│   └── Support/              # أدوات مساعدة
├── bootstrap/                # تهيئة التطبيق (app.php, providers.php)
├── config/                   # ملفات الإعداد (orders, reservations, ...)
├── database/
│   ├── factories/            # مصانع البيانات
│   ├── migrations/           # مخطط قاعدة البيانات
│   └── seeders/              # البيانات الأولية
├── lang/                     # ملفات الترجمة (ar, en, nl)
├── public/                   # الأصول العامة + manifest.json + sw.js + icons/
├── resources/
│   ├── css/  js/             # أصول Vite
│   └── views/                # قوالب Blade (components, livewire, filament)
├── routes/                   # web.php, console.php
├── tests/                    # Pest tests (Feature, Unit)
└── .github/workflows/        # خطوط CI (tests, lint)
```

---

## 19) خطوات النشر (Production)

1. ضبط `.env` للإنتاج: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` الصحيح، وبيانات DB و SMTP.
2. تثبيت الحزم بإنتاجية: `composer install --no-dev --optimize-autoloader`.
3. بناء الأصول: `npm ci && npm run build`.
4. تنفيذ الترحيلات: `php artisan migrate --force`.
5. إنشاء رابط التخزين: `php artisan storage:link`.
6. تحسين الأداء: `php artisan config:cache && php artisan route:cache && php artisan view:cache`.
7. تفعيل **HTTPS** لضمان عمل PWA و Service Worker.
8. تشغيل عامل الطوابير (Queue Worker) واستمراره في الخلفية.

---

## 20) ملاحظات وتوصيات تشغيلية

- **الأمان:** تأكد من ضبط `APP_DEBUG=false` في الإنتاج، ومن تغيير بيانات المستخدم الافتراضي الناتج عن الـ Seeder.
- **الضريبة:** راجع قيمة `vat_rate` في إعدادات المطعم (افتراضيًا 9% وفق النظام الهولندي).
- **الوقت والمنطقة الزمنية:** يُضبط من `config/app.php` — تأكد من مطابقتها لمنطقة عمل المطعم.
- **الصور والوسائط:** تعتمد على `storage:link` و`APP_URL` الصحيح لعرض الصور بشكل سليم.
- **قاعدة البيانات:** عند تعديل أي عمود في Migration جديدة يجب تضمين كل سماته الأصلية لتجنّب فقدان الخصائص.

---

> لمزيد من التفاصيل التقنية عن كل طبقة، راجع ملفات الكود داخل `app/` ومخططات قاعدة البيانات في `database/migrations/`.
