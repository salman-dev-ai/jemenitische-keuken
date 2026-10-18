# Yemeni Kitchen — Jemenitische Keuken

> A complete web platform for a Yemeni restaurant in the Netherlands: digital menu, ordering cart, table reservations, coupons & offers, photo gallery, and delivery zones — plus a full admin panel built on Filament.

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-5-FDAE4B)](https://filamentphp.com)
[![Livewire](https://img.shields.io/badge/Livewire-4-4E56A6)](https://livewire.laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php&logoColor=white)](https://www.php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-4-38BDF8?logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Pest](https://img.shields.io/badge/Tests-Pest-8B5CF6)](https://pestphp.com)

**Languages:** English · [العربية](README.ar.md)

---

## 📑 Table of Contents

1. Overview
2. Key Features
3. Tech Stack
4. Project Architecture
5. Data Models & Database
6. Admin Panel (Filament)
7. Public Frontend (Livewire)
8. Orders, Pricing & VAT
9. Reservation System
10. Coupons & Promotions
11. Multilingual Support (Localization)
12. Progressive Web App (PWA)
13. Roles & Permissions
14. Requirements & Installation
15. Environment Variables
16. Useful Commands
17. Tests & Continuous Integration
18. Project Structure
19. Production Deployment
20. Operational Notes & Recommendations

---

## 1) Overview

**Yemeni Kitchen (Jemenitische Keuken)** is a single-branch web application designed to run as a Yemeni restaurant in the Netherlands, with an Arabic-first (RTL) public frontend serving customers and a full admin panel serving the restaurant team.

The project combines:

- **An interactive public frontend** built with Livewire + Volt (instant updates without full page reloads).
- **A complete ordering system** (pickup / dine-in / delivery / pre-order) with Dutch VAT (BTW) calculation.
- **A reservation system** that prevents time conflicts and issues a secure reference code.
- **An advanced coupon & promotion system** with usage limits and scopes (product / category / all).
- **A Filament admin panel** with decoupled schemas plus statistics and chart widgets.
- **PWA support** to let customers install the site as an app on their phone home screen.

---

## 2) Key Features

### 🌐 Public Frontend (Customers)

| Feature | Description |
| --- | --- |
| Home page | Animated slider, order-options section, header, and a rich footer with contact info and opening hours |
| Menu & cart | Browse dishes by category, add to cart, edit quantities, and a live order summary |
| Checkout | Order type selection (dine-in / pickup / delivery / pre-order), customer data, and optional payment |
| Reservations | Booking form with validation, Arabic-digit normalization, conflict prevention, and sending booking details to the manager via WhatsApp |
| Coupons | Apply/remove coupons inside the cart, plus "My Coupons" to save coupons to the customer account |
| Promotions | Offers section with featured cards and one-click coupon application to the cart |
| Delivery zones | A page listing delivery regions and costs (`/delivery-zones`) |
| Gallery | Gallery with categories, filtering, and featured items |
| Customer reviews | Animated review marquee |
| Contact us | Contact form stored in the database |
| "Remember me" | Saves customer details in a secure cookie (90 days) to auto-fill on future visits |
| Language switcher | Instant switching between Arabic / English / Dutch via `/lang/switch/{locale}` |

### 🎛️ Admin Panel (Team)

| Feature | Description |
| --- | --- |
| Dashboard | Restaurant status widget + charts for sales, order statuses, and order types |
| Menu management | Translatable categories and dishes, drag-and-drop ordering, image uploads, allergen tracking |
| Order management | Status tracking, price editing, automatic VAT calculation |
| Reservation management | Status (pending / confirmed / seated / cancelled / no-show), linked to customer and table |
| Customer management | Customer records and their relations to orders and reservations |
| Coupons & promotions | Usage limits, scope, expiry, and linked products/categories |
| Content & media | Slider, gallery, and order options |
| Restaurant settings | A single (Singleton) record for address, opening hours, VAT, and enabling orders/reservations |
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

### Application Layers in `app/`

| Directory | Role |
| --- | --- |
| `DTOs/` | Data transfer objects (`CouponResult`, `PricingBreakdown`) |
| `Enums/` | PHP 8 backed enums implementing `HasLabel` and `HasColor` for Filament |
| `Exceptions/` | Domain exceptions (`CouponException`, `OrderException`, `ReservationException`) |
| `Filament/` | Admin panel resources and widgets |
| `Http/` | Language controller and locale middleware |
| `Livewire/` | Public frontend components + traits in `Concerns/` |
| `Models/` | Eloquent models |
| `Services/` | Business logic (orders, pricing, coupons, reservations) |
| `Support/` | Helpers (`OpeningHoursPresenter`) |

---

## 5) Data Models & Database

### 5.1 Main Tables

The schema is built via migrations and includes the following tables:

- **System:** `users`, `cache`, `jobs`
- **Settings:** `restaurant_settings`
- **Menu:** `menu_categories`, `menu_items`
- **Orders:** `orders`, `order_items`, `order_options`
- **Reservations:** `reservations`
- **Customers:** `customers`
- **Coupons:** `coupons`, `coupon_usages`, `coupon_product`, `coupon_category`
- **Promotions & marketing:** `promotions`, `sliders`, `gallery_items`
- **Contact:** `contact_messages`
- **Delivery:** `delivery_zones`
- **Permissions:** `spatie/laravel-permission` tables

### 5.2 Key Models

| Model | Description & notable relations |
| --- | --- |
| `RestaurantSetting` | Singleton record with automatic caching; exposes `current()`, `vatRate()`, `maxQuantityPerItem()` |
| `MenuCategory` | Translatable categories; `menuItems` (HasMany) |
| `MenuItem` | Dishes with pricing, allergens, featured flag, and availability; scopes: `available()`, `featured()` |
| `Order` | The financial record of an order; relations `items`, `reservation`, `customer`, `coupon`; stores a coupon snapshot |
| `OrderItem` | Order line items preserving the historical `unit_price` |
| `OrderOption` | Order options (image, title, icon, active flag) |
| `Reservation` | Booking with a secure reference code; scopes: `forDate()`, `pending()` |
| `Customer` | Customer data; relations `reservations`, `orders`, `couponUsages`; scopes: `new()`, `returning()` |
| `Coupon` | Coupon with usage conditions and scope; relations `usages`, `menuItems`, `menuCategories`, `promotion` |
| `CouponUsage` | Tracks a customer's coupon usage with statuses (Claimed/Used/Expired/Cancelled) |
| `Promotion` | Offers linked to coupons with media (image/video) and validity windows |
| `Slider` | Home page slides |
| `GalleryItem` | Gallery items with categories, featured flag, and view counter |
| `DeliveryZone` | Delivery regions and their cost by region/postal code |
| `ContactMessage` | Contact form submissions |
| `User` | System user; controls access to the Filament panel |

### 5.3 Notable Architectural Decisions

- **Automatic reference codes** (e.g. `RES-…`, `ORD-…`) generated via Eloquent events (`booted`) to prevent IDOR vulnerabilities.
- **Financial record integrity:** storing a unit-price snapshot in `order_items` and the coupon code/type in `orders`.
- **Modern casts:** using the `casts()` method instead of the legacy `$casts` property.
- **Native localization:** storing translatable fields in JSON columns via `spatie/laravel-translatable`.

---

## 6) Admin Panel (Filament)

The configuration is fully defined in `AdminPanelProvider`, including:

- **Path:** `/admin` with a built-in login page.
- **Brand identity:** primary color `#D47716` (gold/orange) and dark brown `#3E1F15`, with the **Tajawal** font and RTL support.
- **Automatic discovery** of resources, pages, and widgets.
- **Translation plugin** `SpatieTranslatablePlugin` with locales (Arabic / English / Dutch).
- **Single-record lockdown** in `RestaurantSettingResource` (create and delete disabled).
- **UX details:** collapsible sidebar, full content width, unsaved-changes alerts, and a `Ctrl/Cmd + K` global search shortcut.

### Resources

`Coupons` · `Customers` · `DeliveryZones` · `GalleryItems` · `MenuCategories` · `MenuItems` · `OrderOptions` · `Orders` · `Promotions` · `Reservations` · `RestaurantSettings` · `Sliders` · `Users`

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

## 7) Public Frontend (Livewire)

### Components

| Component | Purpose |
| --- | --- |
| `HomePage` | Home page: slider and order options |
| `MenuWithCart` | Main component: menu + cart + reservation + checkout |
| `MenuCategories` | Display and filter menu categories |
| `ReservationForm` | Standalone reservation form with a WhatsApp notification to the manager |
| `GallerySection` | Gallery with categories and filtering |
| `CustomerReviewsMarquee` | Animated customer reviews marquee |
| `Footer` | Footer with contact info, opening hours, and language switching |
| `ShowDeliveryZones` | Display delivery zones |
| `ContactForm` | Contact form and message storage |

### Splitting `MenuWithCart` Logic into Traits

To keep the component thin, the logic is distributed across traits in `app/Livewire/Concerns`:

| Trait | Responsibility |
| --- | --- |
| `ManagesCart` | Add/update/remove cart items and compute totals |
| `ManagesCoupon` | Apply and remove coupons inside the cart |
| `ManagesMyCoupons` | The "My Coupons" modal: saving/copying coupons |
| `ManagesMenu` | Display dishes, categories, and filtering |
| `ValidatesCheckout` | Validation rules, messages, and order-type normalization |
| `ProcessesCheckout` | Finalizing the order: building reservation/order data and the WhatsApp message |
| `RemembersCustomer` | The "remember me" cookie, saving/updating the customer, and phone normalization |

> Architectural note: the traits depend on the component's properties (such as `$cart` and `$order_type`), so any new property must be declared on the component itself, not inside a trait.

---

## 8) Orders, Pricing & VAT

### Order Types (`OrderType`)

`pickup` · `dine_in` · `delivery` · `preorder`

### Order Statuses (`OrderStatus`)

`pending` · `processing` · `completed` · `cancelled`

### Pricing Formula (in `PricingService`)

```
subtotal  = Σ (unit_price × quantity)
discount  = min(coupon_value , subtotal)
taxable   = subtotal − discount
vat       = taxable × (vat_rate ÷ 100)
total     = taxable + vat
```

- VAT is calculated only through `PricingService` (no duplication in the UI or resources).
- The restaurant VAT rate is read from `RestaurantSetting.vat_rate` (defaults to `9.00`, i.e. 9% — the Dutch BTW for restaurants).
- The order creation flow (`OrderService::createOrder`) runs inside a single transaction (`DB::transaction`) with `lockForUpdate` on menu items to prevent price changes mid-process; coupon operations (claim + redeem) execute within the same transaction.

---

## 9) Reservation System

- Reservation logic is handled by `ReservationService` inside a database transaction.
- **Conflict check:** prevents booking a time slot that overlaps with an existing reservation on the same day, using a configurable default duration (`RESERVATION_DURATION_MINUTES`, default 90 minutes).
- **Reference codes:** generates a secure code (e.g. `RES-…`) without exposing sequential numbering.
- **Reservation statuses (`ReservationStatus`):** `pending` · `confirmed` · `seated` · `cancelled` · `no_show`.
- **Security:** rate limiting by IP address, and normalization of Arabic phone digits to Latin digits.
- **WhatsApp notification:** after a successful booking, a `wa.me` link is built with the reservation details and sent to the restaurant number stored in settings.

---

## 10) Coupons & Promotions

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

## 11) Multilingual Support (Localization)

The project supports three languages: **Arabic** (default), English, and Dutch.

- Translation files live in `lang/ar`, `lang/en`, `lang/nl` and include: `messages`, `validation`, `passwords`, `pagination`, `auth`, `footer`, `delivery`, `cart`, `offers`, `coupons`, `enums`, `gallery`, `my-coupons`.
- The locale is determined by the `SetLocale` middleware in this order: **session → browser preference → default locale**.
- Instant switching via `LanguageController::switch($locale)` from the `lang/switch/{locale}` route.
- Translated database content (category, dish, and offer names) is stored in JSON columns via `spatie/laravel-translatable`.

---

## 12) Progressive Web App (PWA)

- Manifest file: `public/manifest.json` (app name, colors, icons, `display: standalone`, RTL direction).
- Icons: `public/icons/` (including `icon-192`, `icon-512`, `icon-maskable-512`, `apple-touch-icon`).
- Service Worker: `public/sw.js`, registered automatically from the layout scripts partial.
- PWA meta tags in `<head>`: `manifest`, `theme-color`, `apple-mobile-web-app-*`.

> ⚠️ For app installation and the service worker to work, the site must be served over **HTTPS** in production.

---

## 13) Roles & Permissions

`RolesAndPermissionsSeeder` sets up the following roles and permissions:

| Role | Scope |
| --- | --- |
| `super_admin` | All permissions |
| `admin` | Menu, orders, customers, reservations, gallery, and restaurant settings |
| `content_manager` | Menu categories & items + gallery only |

Permissions are grouped per resource (view / create / edit / delete), e.g. `menu_items.*`, `orders.*`, `reservations.*`, `customers.*`, `gallery.*`, plus `restaurant_settings.view/edit` and `users.*`.

> ⚠️ **Security notice:** the seeder creates a default user `admin@jemenitische-keuken.nl` with a default password. Change the password immediately after the first deployment and never use seeder credentials in production.

---

## 14) Requirements & Installation

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

## 15) Environment Variables

The most relevant project variables (in addition to standard Laravel settings):

| Variable | Description | Default |
| --- | --- | --- |
| `APP_NAME` | Application name | `Jemenitische Keuken` |
| `APP_URL` | Application URL (important for storage links and images) | `http://localhost` |
| `APP_LOCALE` | Default locale | `ar` |
| `APP_FALLBACK_LOCALE` | Fallback locale | `en` |
| `ORDER_VAT_RATE` | VAT rate as a decimal fraction | `0.09` |
| `ORDER_MAX_QTY_PER_ITEM` | Maximum quantity per single dish | `50` |
| `RESERVATION_DURATION_MINUTES` | Default reservation duration in minutes | `90` |
| `RESERVATION_COOKIE_SECURE` | Force the "remember me" cookie over HTTPS | `true` |
| `DB_*` | Database settings (MySQL) | — |
| `QUEUE_CONNECTION` | Queue driver | `database` |
| `SESSION_DRIVER` / `CACHE_STORE` | Session and cache stores | `database` |

Important notes when configuring `.env`:

- Do **not** add spaces around `=` (e.g. `DB_HOST = 127.0.0.1`); they cause database connection failures.
- In production: `APP_ENV=production` and `APP_DEBUG=false`.
- Make sure `APP_URL` is correct so that `storage:link` and image URLs work properly.

---

## 16) Useful Commands

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

## 17) Tests & Continuous Integration

- Test framework: **Pest 3**, with tests under `tests/Feature` and `tests/Unit`.
- Notable tests:
  - `PwaInstallationTest` — verifies the home page response.
  - `RestaurantSettingSingletonTest` — ensures no duplicate settings records and that caching works.
  - Authentication, profile, and coupon tests.
- **CI:** GitHub Actions workflows in `.github/workflows`:
  - `tests.yml` — runs `pest` on PHP 8.4 with asset building.
  - `lint.yml` — checks code formatting.

```bash
php artisan test --compact
```

---

## 18) Project Structure

```
jemenitische-keuken-chat/
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
│   └── Support/              # Helpers
├── bootstrap/                # App bootstrap (app.php, providers.php)
├── config/                   # Config files (orders, reservations, ...)
├── database/
│   ├── factories/            # Model factories
│   ├── migrations/           # Database schema
│   └── seeders/              # Seed data
├── lang/                     # Translation files (ar, en, nl)
├── public/                   # Public assets + manifest.json + sw.js + icons/
├── resources/
│   ├── css/  js/             # Vite assets
│   └── views/                # Blade templates (components, livewire, filament)
├── routes/                   # web.php, console.php
├── tests/                    # Pest tests (Feature, Unit)
└── .github/workflows/        # CI workflows (tests, lint)
```

---

## 19) Production Deployment

1. Configure `.env` for production: `APP_ENV=production`, `APP_DEBUG=false`, the correct `APP_URL`, plus DB and SMTP credentials.
2. Install production dependencies: `composer install --no-dev --optimize-autoloader`.
3. Build assets: `npm ci && npm run build`.
4. Run migrations: `php artisan migrate --force`.
5. Create the storage link: `php artisan storage:link`.
6. Optimize performance: `php artisan config:cache && php artisan route:cache && php artisan view:cache`.
7. Enable **HTTPS** so the PWA and service worker function correctly.
8. Run and keep a queue worker running in the background.

---

## 20) Operational Notes & Recommendations

- **Security:** make sure `APP_DEBUG=false` in production, and change the default seeder user credentials.
- **VAT:** review the `vat_rate` value in restaurant settings (defaults to 9% under Dutch regulations).
- **Time zone:** set it in `config/app.php` — make sure it matches the restaurant's operating region.
- **Images & media:** rely on `storage:link` and a correct `APP_URL` for proper image display.
- **Database:** when modifying a column in a new migration, include all of its original attributes to avoid losing properties.

---

> For more technical details on any layer, refer to the code under `app/` and the database schema in `database/migrations/`.
