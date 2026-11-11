# Yemeni Kitchen — Jemenitische Keuken

> A complete, production-ready web platform for a Yemeni restaurant in the Netherlands: digital menu, ordering cart, table reservations, coupons & offers, photo gallery, delivery zones, GDPR cookie consent, and full SEO — plus a complete admin panel built on Filament.

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-5-FDAE4B)](https://filamentphp.com)
[![Livewire](https://img.shields.io/badge/Livewire-4-4E56A6)](https://livewire.laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php&logoColor=white)](https://www.php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-4-38BDF8?logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Pest](https://img.shields.io/badge/Tests-Pest-8B5CF6)](https://pestphp.com)

**Languages:** English · [العربية](README.ar.md)

---

## Table of Contents

1. [Overview](#1-overview)
2. [Key Features](#2-key-features)
3. [Tech Stack](#3-tech-stack)
4. [Project Architecture](#4-project-architecture)
5. [Multilingual Routing & Localization](#5-multilingual-routing--localization)
6. [Search Engine Optimization (SEO)](#6-search-engine-optimization-seo)
7. [Cookie Consent (GDPR)](#7-cookie-consent-gdpr)
8. [Allergens & Dietary Labels](#8-allergens--dietary-labels)
9. [Free Drink System](#9-free-drink-system)
10. [Orders, Pricing & VAT](#10-orders-pricing--vat)
11. [Reservation System](#11-reservation-system)
12. [Coupons & Promotions](#12-coupons--promotions)
13. [Delivery Zones](#13-delivery-zones)
14. [Data Models & Database](#14-data-models--database)
15. [Admin Panel (Filament)](#15-admin-panel-filament)
16. [Public Frontend (Livewire)](#16-public-frontend-livewire)
17. [Progressive Web App (PWA)](#17-progressive-web-app-pwa)
18. [Roles & Permissions](#18-roles--permissions)
19. [Requirements & Installation](#19-requirements--installation)
20. [Environment Variables](#20-environment-variables)
21. [Useful Commands](#21-useful-commands)
22. [Tests & Continuous Integration](#22-tests--continuous-integration)
23. [Project Structure](#23-project-structure)
24. [Production Deployment](#24-production-deployment)
25. [Operational Notes](#25-operational-notes)

---

## 1) Overview

**Yemeni Kitchen (Jemenitische Keuken)** is a single-branch web application for a Yemeni restaurant in the Netherlands. It delivers:

- **An interactive public frontend** built with Livewire + Volt (instant updates without full page reloads), with a fully multilingual experience (Dutch / English / Arabic) and RTL support.
- **A complete ordering system** (dine-in / pickup / delivery / pre-order) with Dutch VAT (BTW) calculation.
- **A reservation system** that prevents time conflicts and issues a secure reference code.
- **An advanced coupon & promotion system** with usage limits and scopes (product / category / all).
- **A Filament admin panel** with decoupled schemas, statistics, and chart widgets.
- **Production-grade SEO**: locale-aware URLs, `hreflang`, sitemap, JSON-LD structured data, and per-language meta overrides.
- **GDPR cookie consent** with server-side enforcement and consent logging.
- **PWA support** so customers can install the site as an app on their phone home screen.

---

## 2) Key Features

### 🌐 Public Frontend (Customers)

| Feature | Description |
| --- | --- |
| Home page | Animated hero slider, order-options section, popular dishes, and a rich footer with contact info and opening hours |
| Menu & cart | Browse dishes by category, add to cart, edit quantities, and a live order summary |
| Checkout | Order type selection (dine-in / pickup / delivery / pre-order), customer data, and optional payment |
| Free drink offers | Eligible dishes can include free drinks selected by the customer (server-validated) |
| Reservations | Booking form with validation, Arabic-digit normalization, conflict prevention, and sending booking details to the manager via WhatsApp |
| Coupons | Apply/remove coupons inside the cart, plus "My Coupons" to save coupons to the customer account |
| Promotions | Offers section with featured cards and one-click coupon application to the cart |
| Delivery zones | Delivery regions and costs, now shown inside the Restaurant Info page |
| Restaurant info | `/restaurant-info`: opening hours, allergen information, and the delivery table |
| Gallery | Gallery with categories, filtering, and featured items |
| Customer reviews | Animated review marquee |
| Contact us | Contact form stored in the database |
| "Remember me" | Saves customer details in a secure, first-party cookie (90 days) to auto-fill on future visits |
| Language switcher | Instant switching between Dutch / English / Arabic; each language has its own URL prefix |

### 🎛️ Admin Panel (Team)

| Feature | Description |
| --- | --- |
| Dashboard | Restaurant status widget + charts for sales, order statuses, and order types |
| Menu management | Translatable categories and dishes, drag-and-drop ordering, image uploads, free-drink configuration |
| Allergens catalog | Manageable EU-14 allergens + dietary labels, linked to dishes (many-to-many), with Lucide icons |
| Order management | Status tracking, price editing, automatic VAT calculation, free-drink snapshots |
| Reservation management | Status (pending / confirmed / seated / cancelled / no-show), linked to customer and table |
| Customer management | Customer records and their relations to orders and reservations |
| Coupons & promotions | Usage limits, scope, expiry, and linked products/categories |
| Content & media | Slider, gallery, and order options |
| Restaurant settings | A single (Singleton) record for contact info, opening hours, VAT, order settings, and all SEO fields |
| Cookie consents | Read-only GDPR consent log (analytics / marketing choices) |
| Users & permissions | User management with a roles/permissions system (Spatie Permission) |

---

## 3) Tech Stack

| Layer | Technology | Version |
| --- | --- | --- |
| Core framework | Laravel | 12 |
| Language | PHP | 8.2+ |
| UI components | Livewire | 4 |
| Single-file components | Livewire Volt | 1 |
| UI component library | Livewire Flux (Free) | 2 |
| Admin panel | Filament | 5 |
| Styling | Tailwind CSS | 4 |
| Icons | mallardduck/blade-lucide-icons | 2 |
| Localization | spatie/laravel-translatable + lara-zeus/spatie-translatable | 6 / — |
| Permissions | spatie/laravel-permission | 6 |
| Bundler | Vite | 6 |
| Testing | Pest | 3 |
| Code formatter | Laravel Pint | 1 |

---

## 4) Project Architecture

The project follows a clear separation of concerns:

```
┌─────────────────────────────────────────────────────────────┐
│                     Public Frontend                          │
│   Livewire Components  →  Blade Views  →  Tailwind/Alpine    │
└───────────────────────────┬─────────────────────────────────┘
                            │
┌───────────────────────────▼─────────────────────────────────┐
│                   Service Layer                              │
│   OrderService · CouponService · PricingService              │
│   ReservationService                                         │
└───────────────────────────┬─────────────────────────────────┘
                            │
┌───────────────────────────▼─────────────────────────────────┐
│                     Support Layer                            │
│   SeoMetadata · OpeningHoursPresenter · GoogleMapsLink       │
└───────────────────────────┬─────────────────────────────────┘
                            │
┌───────────────────────────▼─────────────────────────────────┐
│                  Models & Logic                              │
│   Order · OrderItem · Reservation · Coupon · Customer ...    │
│   Enums (OrderStatus, OrderType, ...) + DTOs                 │
└───────────────────────────┬─────────────────────────────────┘
                            │
┌───────────────────────────▼─────────────────────────────────┐
│            Data Layer (MySQL) + Cache + Queue                │
└─────────────────────────────────────────────────────────────┘

        The Filament admin panel interacts directly with
        models and services to reuse the same numbers and logic.
```

Design principles applied:

- **KISS / YAGNI:** a single-branch architecture without unnecessary relational complexity.
- **Strict typing:** `declare(strict_types=1)` with explicit parameter and return types.
- **Schema separation:** Filament forms/tables are split into `Schemas/` and `Tables/` to keep Resources thin.
- **Financial data integrity:** snapshotting unit price and coupon values at order time.
- **Race-condition safety:** `DB::transaction` with `lockForUpdate` in coupon and order operations.
- **Server-side validation:** all business rules (coupons, free drinks, reservations, cookie consent) are re-validated on the server; the UI is never trusted.

### Application Layers in `app/`

| Directory | Role |
| --- | --- |
| `DTOs/` | Data transfer objects (`CouponResult`, `PricingBreakdown`) |
| `Enums/` | PHP 8 backed enums implementing `HasLabel` and `HasColor` for Filament |
| `Exceptions/` | Domain exceptions (`CouponException`, `OrderException`, `ReservationException`) |
| `Filament/` | Admin panel resources and widgets |
| `Http/` | Controllers (language, sitemap, robots, cookie consent) and middleware (`SetLocale`, `ShareCookieConsent`) |
| `Livewire/` | Public frontend components + traits in `Concerns/` |
| `Models/` | Eloquent models |
| `Services/` | Business logic (orders, pricing, coupons, reservations) |
| `Support/` | Helpers (`SeoMetadata`, `OpeningHoursPresenter`, `GoogleMapsLink`) |

---

## 5) Multilingual Routing & Localization

The site serves **three languages**, each with its own URL prefix so search engines can index an independent, correctly-tagged version per language.

| Locale | URL prefix | Notes |
| --- | --- | --- |
| Dutch (`nl`) | `/nl` | **Default locale** (local market language in the Netherlands) |
| English (`en`) | `/en` | |
| Arabic (`ar`) | `/ar` | RTL layout |

**Single source of truth:** `config/locales.php` defines `supported` (`['nl', 'en', 'ar']`), `default` (`nl`), display `labels`, and `og_locale` values. It is reused by routing, hreflang tags, `sitemap.xml`, and the language switcher.

**URL behavior:**

- The root `/` issues a **permanent redirect (301)** to `/{default}` (i.e. `/nl`) to avoid duplicate content.
- Every page lives under a language prefix: `/{locale}/` and `/{locale}/restaurant-info`.
- The old `/delivery-zones` route is **permanently redirected (301)** to `/{locale}/restaurant-info`, preserving any existing backlinks.
- `route('home')` automatically generates the correct localized URL because `SetLocale` sets `URL::defaults(['locale' => ...])`.

**Locale resolution order** (`App\Http\Middleware\SetLocale`):

1. **Route prefix** (`{locale}`) — highest priority, the SEO-friendly source.
2. **Session** — the visitor's previous choice.
3. **Browser preferred language** (`Accept-Language`).
4. **Default locale** (`nl`).

Any unsupported value falls back to the default locale. The chosen locale is stored in the session so Livewire requests (which carry no prefix) stay consistent.

**Translation files:** `lang/{ar,en,nl}` include `messages`, `validation`, `passwords`, `pagination`, `auth`, `footer`, `delivery`, `cart`, `offers`, `coupons`, `enums`, `gallery`, `my-coupons`, `about`, `info`, and `seo`.

**Translated database content** (category, dish, allergen, promotion names) is stored in JSON columns via `spatie/laravel-translatable`.

**Language switching:** `LanguageController::switch($locale)` at `GET /lang/switch/{locale}` stores the choice in the session and redirects back. (Direct localized URLs remain the SEO-preferred way to switch.)

---

## 6) Search Engine Optimization (SEO)

SEO is centralized in `App\Support\SeoMetadata`, a single class that produces all head tags for a page:

- **Title & description** with priority: admin override (Filament) → page text from `lang/{locale}/seo.php` → site name.
- **Canonical URL** for the current page and language.
- **`hreflang` alternates** for every supported locale, plus **`x-default`** pointing to the default locale.
- **Open Graph** tags (`og:type`, `og:site_name`, `og:title`, `og:description`, `og:url`, `og:image`, `og:locale`, `og:locale:alternate`).
- **Twitter Card** tags (including `twitter:site` when an X/Twitter link is set).
- **JSON-LD structured data** of type `Restaurant` (Schema.org): name, URL, contact, postal address, cuisines, price range, opening hours, social `sameAs`, map link, and geo coordinates — only real values from restaurant settings are emitted (no fabricated ratings or prices).
- **Google Search Console verification** meta tag.

**Generated files:**

| Route | Controller | Purpose |
| --- | --- | --- |
| `GET /sitemap.xml` | `SitemapController` | Sitemap for every page × every locale, each `<url>` carrying its `hreflang` alternates and `x-default`. `lastmod` is intentionally omitted (no real modification dates). |
| `GET /robots.txt` | `RobotsController` | Dynamic robots file with an absolute `Sitemap:` URL; disallows `/admin`, `/livewire`, and `/lang/switch`. |

**SEO fields** (managed from the Restaurant Settings resource): `meta_title`, `meta_description` (translatable), `og_image`, `price_range`, `serves_cuisine`, `social_links`, `latitude`, `longitude`, `google_site_verification`, plus tracking IDs (`google_analytics_id`, `meta_pixel_id`).

> Admin overrides for `meta_title` / `meta_description` apply to the home page only; other pages use the translations in `lang/{locale}/seo.php`.

---

## 7) Cookie Consent (GDPR)

A first-party, server-enforced consent system (Telecommunicatiewet / GDPR compliant).

- **Config:** `config/cookies.php` — cookie name `cookie_consent`, lifetime 180 days, policy `version` (bumping it forces re-consent), and cookie purge lists.
- **Categories:** `necessary` (always on), `analytics`, `marketing`.
- **Controller:** `CookieConsentController@store` (`POST /cookie-consent`, throttled `30,1`) performs **strict server-side validation**, writes a secure first-party cookie (`HttpOnly`, `SameSite=Lax`, `Secure` on HTTPS, auto-encrypted), and records the consent.
- **Proof of consent:** logged in the `cookie_consents` table (read-only in the admin panel). The visitor's IP is stored **hashed (SHA-256 + app key)** rather than raw, to minimize data.
- **Middleware:** `ShareCookieConsent` reads the encrypted cookie and shares a normalized `$cookieConsent` array with all views — the frontend never relies on `localStorage`.
- **Script gating:** `resources/views/components/layouts/partials/cookie-consent-scripts.blade.php` loads Google Analytics 4 (`anonymize_ip`) and Meta Pixel **only after consent**, sets Google Consent Mode defaults/updates, and purges the relevant cookies when consent is revoked.

---

## 8) Allergens & Dietary Labels

Allergy information is a **single managed dictionary**, not free text:

- **`Allergen` model** — translatable name, `type`, Lucide `icon` (or uploaded image), `is_active`, and `sort_order`.
- **`AllergenType` enum** — `Allergen` (allergy trigger) and `Label` (dietary label), each with a Filament badge color.
- **Default catalog** (`Allergen::DEFAULT_CATALOG`) ships the **EU-14 allergens** (gluten, crustaceans, eggs, fish, peanuts, soybeans, milk, nuts, celery, mustard, sesame, sulphites, lupin, molluscs) plus dietary labels (halal, vegan, vegetarian, lactose-free, gluten-free, nut-free, sugar-free).
- **Many-to-many relation** via the `allergen_menu_item` pivot; dishes expose `activeAllergens` (active only, ordered) to avoid N+1 queries.
- Allergy information is displayed to customers on the menu and on the `/restaurant-info` page.

---

## 9) Free Drink System

Certain dishes can be configured to include free drinks:

- **Configuration on `MenuItem`:** `offers_free_drink`, `free_drink_qty`, `free_drink_policy`, `free_drink_item_ids`.
- **Policies:**
  - `per_unit` (default) — free drinks scale with quantity: `qty × quantity`.
  - `per_line` — a fixed number of free drinks regardless of quantity.
- **Eligible drinks** must belong to a category flagged `is_drinks_category` (see `MenuCategory::scopeDrinks()`), so the system always knows which items may be given away.
- **Server-side enforcement:** `OrderService` re-validates every selection (`buildFreeDrinkOptions`, `freeDrinkSlotsFor`) and throws `OrderException::freeDrinkNotEligible` / `freeDrinkQuantityExceeded` when rules are violated. The chosen drinks are stored as a **price‑0 snapshot** on the order line, so historical orders remain accurate.
- **Cart behavior:** if only one drink is allowed, it is selected automatically; if multiple are allowed, the customer chooses with a clear notice.

---

## 10) Orders, Pricing & VAT

### Order Types (`OrderType`)

`pickup` · `dine_in` · `delivery` · `preorder`

### Order Statuses (`OrderStatus`)

`pending` · `processing` · `completed` · `cancelled`

### Pricing Formula (in `PricingService`)

```
subtotal  = Σ (unit_price × quantity)
discount  = min(coupon_value , subtotal)
taxable   = subtotal − discount
vat       = taxable × vat_rate
total     = taxable + vat
```

- VAT is calculated **only** through `PricingService` (no duplication in the UI or resources).
- The restaurant VAT rate is read from `RestaurantSetting.vat_rate` (a percentage, default `9.00` → 9% — the Dutch BTW for restaurants).
- The order creation flow (`OrderService::createOrder`) runs inside a single transaction (`DB::transaction`) with `lockForUpdate` on menu items to prevent price changes mid-process; coupon operations (claim + redeem) and free-drink validation execute within the same transaction.
- Order numbers (e.g. `ORD-…`) are generated automatically to avoid exposing sequential IDs.

---

## 11) Reservation System

- Reservation logic is handled by `ReservationService` inside a database transaction.
- **Conflict check:** prevents booking a time slot that overlaps an existing reservation on the same day, using a configurable default duration (`RESERVATION_DURATION_MINUTES`, default 90 minutes), backed by a dedicated conflict index on the `reservations` table.
- **Reference codes:** generates a secure code (e.g. `RES-…`) without exposing sequential numbering.
- **Reservation statuses (`ReservationStatus`):** `pending` · `confirmed` · `seated` · `cancelled` · `no_show`.
- **Security:** rate limiting by IP address and normalization of Arabic phone digits to Latin digits.
- **WhatsApp notification:** after a successful booking, a `wa.me` link is built with the reservation details and sent to the restaurant number stored in settings.

---

## 12) Coupons & Promotions

### Coupons

- **Discount types (`DiscountType`):** `Percentage` · `Fixed`, with an `apply()` helper for computing the value.
- **Scope (`CouponScope`):** `All` (whole order) · `Products` (specific items) · `Categories` (specific categories).
- **Usage statuses (`CouponUsageStatus`):** `Claimed` · `Used` · `Expired` · `Cancelled`.
- **Configurable conditions:** minimum order total, total usage limit, per-customer limit, expiry date, and a welcome coupon (`is_welcome`).
- **Concurrency guarantees:** the `coupon_usages` table prevents double usage, and `lockForUpdate` with atomic counter increments is used.

### Promotions (`Promotion`)

- Linked to coupons/offers with image or video support, a start/end window, and a featured flag.
- Media types (`MediaType`): `Image` · `Video`.

`CouponService` is responsible for: validation (`validate`), calculation (`calculateDiscount`), claiming to the customer account (`claim`), marking as used (`redeem`), and releasing on cancellation (`release`).

---

## 13) Delivery Zones

- **`DeliveryZone` model** — `region_name`, `postal_code`, `delivery_cost`, `minimum_order` (both costs returned with 2 decimals).
- Delivery regions and costs are shown to customers inside the **Restaurant Info** page (`/restaurant-info`).
- The legacy `/delivery-zones` URL is **301-redirected** to `/restaurant-info` to keep archived links alive.

---

## 14) Data Models & Database

### 14.1 Main Tables

- **System:** `users`, `cache`, `jobs`
- **Settings:** `restaurant_settings`
- **Menu:** `menu_categories`, `menu_items`
- **Allergens:** `allergens`, `allergen_menu_item`
- **Orders:** `orders`, `order_items`, `order_options`
- **Reservations:** `reservations`
- **Customers:** `customers`
- **Coupons:** `coupons`, `coupon_usages`, `coupon_product`, `coupon_category`
- **Promotions & marketing:** `promotions`, `sliders`, `gallery_items`
- **Contact:** `contact_messages`
- **Privacy:** `cookie_consents`
- **Delivery:** `delivery_zones`
- **Permissions:** `spatie/laravel-permission` tables

### 14.2 Key Models

| Model | Description & notable relations |
| --- | --- |
| `RestaurantSetting` | Singleton record with automatic caching; exposes `current()`, `vatRate()`, `maxQuantityPerItem()`; holds contact info, opening hours, VAT, order settings, SEO fields, and tracking IDs |
| `MenuCategory` | Translatable categories; `menuItems` (HasMany); `is_drinks_category` flag with `scopeDrinks()` |
| `MenuItem` | Dishes with pricing, allergens, featured flag, availability, and free-drink config; scopes: `available()`, `featured()`; relations: `category`, `activeAllergens` |
| `Allergen` | Allergy/label dictionary; `menuItems` (BelongsToMany); `isDietaryLabel()`, `iconName()` |
| `Order` | The financial record of an order; relations `items`, `reservation`, `customer`, `coupon`; stores a coupon snapshot |
| `OrderItem` | Order line items preserving the historical `unit_price` and free-drink snapshot |
| `OrderOption` | Order options (image, title, icon, active flag) |
| `Reservation` | Booking with a secure reference code; scopes: `forDate()`, `pending()` |
| `Customer` | Customer data; relations `reservations`, `orders`, `couponUsages`; scopes: `new()`, `returning()` |
| `Coupon` | Coupon with usage conditions and scope; relations `usages`, `menuItems`, `menuCategories`, `promotion` |
| `CouponUsage` | Tracks a customer's coupon usage with statuses (Claimed/Used/Expired/Cancelled) |
| `Promotion` | Offers linked to coupons with media (image/video) and validity windows |
| `Slider` | Home page slides |
| `GalleryItem` | Gallery items with categories, featured flag, and view counter |
| `DeliveryZone` | Delivery regions and their cost / minimum order |
| `ContactMessage` | Contact form submissions |
| `CookieConsent` | GDPR consent log (uuid, categories, version, hashed IP, user agent) |
| `User` | System user; controls access to the Filament panel |

### 14.3 Notable Architectural Decisions

- **Automatic reference codes** (e.g. `RES-…`, `ORD-…`) generated via Eloquent events (`booted`) to prevent IDOR vulnerabilities.
- **Financial record integrity:** storing a unit-price snapshot in `order_items` and the coupon code/type in `orders`.
- **Modern casts:** using the `casts()` method instead of the legacy `$casts` property.
- **Native localization:** storing translatable fields in JSON columns via `spatie/laravel-translatable`.
- **Database portability guard:** migrations that use MySQL-specific syntax include a DB-driver check.

---

## 15) Admin Panel (Filament)

The configuration is fully defined in `AdminPanelProvider`:

- **Path:** `/admin` with a built-in login page.
- **Brand identity:** primary color `#D47716` (gold/orange) and dark brown `#3E1F15`, the **Tajawal** font, custom logo styling, and RTL support.
- **Automatic discovery** of resources, pages, and widgets.
- **Translation plugin** `SpatieTranslatablePlugin` with locales (English / Arabic / Dutch).
- **Single-record lockdown** in `RestaurantSettingResource` (create and delete disabled).
- **UX details:** collapsible sidebar, full content width, unsaved-changes alerts, and a `Ctrl/Cmd + K` global search shortcut.

### Resources

`Allergens` · `CookieConsents` · `Coupons` · `Customers` · `DeliveryZones` · `GalleryItems` · `MenuCategories` · `MenuItems` · `OrderOptions` · `Orders` · `Promotions` · `Reservations` · `RestaurantSettings` · `Sliders` · `Users`

Each resource is split into:

```
Resources/<Name>/
├── <Name>Resource.php        # Overall definition
├── Pages/                    # List / Create / Edit
├── Schemas/                  # Form schemas
└── Tables/                   # Table definitions
```

### Widgets

| Widget | Purpose |
| --- | --- |
| `RestaurantStatus` | Quick stats: online-orders status, reservations status, and total revenue |
| `RestaurantSalesChart` | Line chart of sales with a last 7 / 30 days toggle |
| `OrderStatusChart` | Doughnut chart of order status distribution |
| `OrderTypesChart` | Bar chart of order types |

---

## 16) Public Frontend (Livewire)

### Components

| Component | Purpose |
| --- | --- |
| `HomePage` | Home page: hero slider and order options |
| `MenuWithCart` | Main component: menu + cart + reservation + checkout |
| `MenuCategories` | Display and filter menu categories |
| `PopularDishes` | Featured dishes section |
| `OffersSection` | Coupons/promotions section with featured cards |
| `ReservationForm` | Standalone reservation form with a WhatsApp notification to the manager |
| `GallerySection` | Gallery with categories and filtering |
| `CustomerReviewsMarquee` | Animated customer reviews marquee |
| `Footer` | Footer with contact info, opening hours, and language switching |
| `ShowDeliveryZones` | Delivery zones display (used on the Restaurant Info page) |
| `ContactForm` | Contact form and message storage |
| `ErrorReport` | Error report component |
| `RestaurantInfo` | Restaurant info page (`/restaurant-info`) |

### Layout & Partials

The base layout `components/layouts/app.blade.php` composes small partials under `components/layouts/partials/` (`head`, `seo`, `helpers`, header parts, `hero`, `footer`, `scroll-to-top`, `cookie-notice`, `cookie-consent-scripts`, `toasts`, `confirm`, `scripts`). Custom error pages exist for `401, 403, 404, 419, 429, 500, 503`.

### Splitting `MenuWithCart` Logic into Traits

To keep the component thin, the logic is distributed across traits in `app/Livewire/Concerns`:

| Trait | Responsibility |
| --- | --- |
| `ManagesCart` | Add/update/remove cart items, compute totals, handle free-drink selections |
| `ManagesCoupon` | Apply and remove coupons inside the cart |
| `ManagesMyCoupons` | The "My Coupons" modal: saving/copying coupons |
| `ManagesMenu` | Display dishes, categories, and filtering |
| `ValidatesCheckout` | Validation rules, messages, and order-type normalization |
| `ProcessesCheckout` | Finalizing the order: building reservation/order data and the WhatsApp message |
| `RemembersCustomer` | The "remember me" cookie, saving/updating the customer, and phone normalization |

> Architectural note: the traits depend on the component's properties (such as `$cart` and `$order_type`), so any new property must be declared on the component itself, not inside a trait.

---

## 17) Progressive Web App (PWA)

- Manifest file: `public/manifest.json` (app name, colors, icons, `display: standalone`, RTL direction).
- Icons: `public/icons/` (including `icon-192`, `icon-512`, `icon-maskable-512`, `apple-touch-icon`).
- Service Worker: `public/sw.js`, registered automatically from the layout scripts partial.
- PWA meta tags in `<head>`: `manifest`, `theme-color` (`#E07513`), and `apple-mobile-web-app-*`.

> ⚠️ For app installation and the service worker to work, the site must be served over **HTTPS** in production.

---

## 18) Roles & Permissions

`RolesAndPermissionsSeeder` sets up the following roles and permissions:

| Role | Scope |
| --- | --- |
| `super_admin` | All permissions |
| `admin` | Menu, orders, customers, reservations, gallery, and restaurant settings |
| `content_manager` | Menu categories & items + gallery only |

Permissions are grouped per resource (view / create / edit / delete), e.g. `menu_items.*`, `orders.*`, `reservations.*`, `customers.*`, `gallery.*`, plus `restaurant_settings.view/edit` and `users.*`.

> ⚠️ **Security notice:** the seeder creates a default user `admin@jemenitische-keuken.nl` with the default password `ChangeThisPassword123!`. Change it immediately after the first deployment and never keep seeder credentials in production.

---

## 19) Requirements & Installation

### Requirements

- PHP 8.2 or newer with the standard Laravel extensions
- Composer
- Node.js 22+ and npm
- A database (MySQL in production; SQLite is supported for testing)

### Installation Steps

```bash
# 1) Install PHP dependencies
composer install

# 2) Set up the environment file and app key
copy .env.example .env
php artisan key:generate

# 3) Install frontend dependencies and build assets
npm install
npm run build

# 4) Set up the database with demo data
php artisan migrate:fresh --seed

# 5) Create the storage link (for menu images and media)
php artisan storage:link
```

### Running the Development Environment

```bash
# Runs at once: Laravel server + queue listener + Vite
composer run dev
```

Or each service separately:

```bash
php artisan serve
npm run dev
php artisan queue:listen --tries=1 --timeout=0
```

---

## 20) Environment Variables

The most relevant project variables (in addition to standard Laravel settings):

| Variable | Description | Default |
| --- | --- | --- |
| `APP_NAME` | Application name | `Jemenitische Keuken` |
| `APP_URL` | Application URL (important for storage links, image URLs, and the absolute sitemap URL) | `http://localhost` |
| `APP_LOCALE` | Framework locale | `en` |
| `APP_FALLBACK_LOCALE` | Fallback locale | `en` |
| `ORDER_VAT_RATE` | Default VAT rate as a decimal fraction (used when no setting overrides it) | `0.09` |
| `ORDER_MAX_QTY_PER_ITEM` | Maximum quantity per single dish | `50` |
| `RESERVATION_DURATION_MINUTES` | Default reservation duration in minutes | `90` |
| `RESERVATION_COOKIE_SECURE` | Force the "remember me" cookie over HTTPS | `true` |
| `DB_*` | Database settings (MySQL) | — |
| `QUEUE_CONNECTION` | Queue driver | `database` |
| `SESSION_DRIVER` / `CACHE_STORE` | Session and cache stores | `database` |

> The **site's routing/SEO default language is `nl`**, defined in `config/locales.php` (not by `APP_LOCALE`, which only sets the framework locale).

Important notes when configuring `.env`:

- Do **not** add spaces around `=` (e.g. `DB_HOST = 127.0.0.1`); they cause database connection failures.
- In production: `APP_ENV=production` and `APP_DEBUG=false`.
- Make sure `APP_URL` is correct so that `storage:link`, image URLs, and `sitemap.xml` work properly.

---

## 21) Useful Commands

| Command | Purpose |
| --- | --- |
| `php artisan migrate:fresh --seed` | Rebuild the database with demo data |
| `php artisan storage:link` | Link the public storage directory (for images) |
| `php artisan test --compact` | Run the test suite |
| `php artisan test --compact --filter=TestName` | Run a specific test |
| `vendor/bin/pint --dirty --format agent` | Format the modified PHP files |
| `php artisan pail` | Tail logs in real time |
| `npm run build` / `npm run dev` | Build / run frontend assets |

---

## 22) Tests & Continuous Integration

- Test framework: **Pest 3**, with tests under `tests/Feature` and `tests/Unit`.
- Notable tests:
  - `SeoTest` — sitemap, robots, canonical, hreflang, and JSON-LD.
  - `RestaurantInfoPageTest` — the `/restaurant-info` page and the `delivery-zones` redirect.
  - `CookieConsentTest` — server-side consent validation and logging.
  - `FreeDrinkOrderTest` — free-drink eligibility and quantity rules.
  - `AllergenMenuLinkTest` — allergen ↔ dish linking.
  - `PwaInstallationTest` — verifies the home page response.
  - `RestaurantSettingSingletonTest` — ensures no duplicate settings records and that caching works.
  - `MyCouponsFilterTest` / `CouponCopyButtonTest` — coupon account features.
  - Authentication, profile, and settings tests.
- **CI:** GitHub Actions workflows in `.github/workflows`:
  - `tests.yml` — runs `pest` on PHP 8.4 with asset building.
  - `lint.yml` — checks code formatting.

```bash
php artisan test --compact
```

---

## 23) Project Structure

```
jemenitische-keuken/
├── app/
│   ├── DTOs/                 # Data transfer objects
│   ├── Enums/                # Backed enums
│   ├── Exceptions/           # Domain exceptions
│   ├── Filament/             # Admin panel resources & widgets
│   ├── Http/                 # Controllers & middleware
│   ├── Livewire/             # Public components + Concerns/
│   ├── Models/               # Eloquent models
│   ├── Providers/            # Service providers (Filament, Volt, App)
│   ├── Services/             # Business logic
│   └── Support/              # Helpers (SEO, opening hours, maps)
├── bootstrap/                # App bootstrap (app.php, providers.php)
├── config/                   # Config files (orders, reservations, locales, cookies, ...)
├── database/
│   ├── factories/            # Model factories
│   ├── migrations/           # Database schema
│   └── seeders/              # Seed data
├── lang/                     # Translation files (ar, en, nl)
├── public/                   # Public assets + manifest.json + sw.js + icons/
├── resources/
│   ├── css/  js/             # Vite assets
│   └── views/                # Blade templates (components, livewire, filament, errors)
├── routes/                   # web.php, console.php
├── tests/                    # Pest tests (Feature, Unit)
└── .github/workflows/        # CI workflows (tests, lint)
```

---

## 24) Production Deployment

1. Configure `.env` for production: `APP_ENV=production`, `APP_DEBUG=false`, the correct `APP_URL`, plus DB and SMTP credentials.
2. Install production dependencies: `composer install --no-dev --optimize-autoloader`.
3. Build assets: `npm ci && npm run build`.
4. Run migrations: `php artisan migrate --force`.
5. Create the storage link: `php artisan storage:link`.
6. Optimize performance: `php artisan config:cache && php artisan route:cache && php artisan view:cache`.
7. Enable **HTTPS** — required for the PWA, service worker, and secure cookies.
8. Run and keep a queue worker running in the background.
9. *(Optional)* Submit `https://your-domain/sitemap.xml` to Google Search Console and set the verification code in Restaurant Settings.

---

## 25) Operational Notes

- **Security:** ensure `APP_DEBUG=false` in production, and change the default seeder user credentials immediately.
- **VAT:** review the `vat_rate` value in restaurant settings (defaults to 9% under Dutch regulations).
- **Time zone:** set it in `config/app.php` — make sure it matches the restaurant's operating region.
- **Languages:** add or reorder languages only via `config/locales.php`; routing, hreflang, and the sitemap update automatically.
- **Images & media:** rely on `storage:link` and a correct `APP_URL` for proper image display.
- **Database:** when modifying a column in a new migration, include all of its original attributes to avoid losing properties.
- **Housekeeping:** the `public/` directory should contain only assets actually used by the app; remove any leftover design/reference artifacts (e.g. saved web-page folders) before delivery, as they bloat the build output.

---

> For more technical details on any layer, refer to the code under `app/` and the database schema in `database/migrations/`.
