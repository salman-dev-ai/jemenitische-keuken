<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;

/**
 * مزوّد لوحة التحكم (Admin Panel) الخاصة بمطعم "المطبخ اليمني".
 *
 * هذا الملف هو المكان الوحيد الذي تُضبط منه إعدادات اللوحة:
 * الهوية البصرية، اكتشاف الموارد والصفحات والويدجت، الـ Middleware،
 * الإضافات، وروابط العرض المخصصة (Render Hooks).
 */
class AdminPanelProvider extends PanelProvider
{
    /** ألوان الهوية المستخرجة من شعار المطعم (تُستخدم في أكثر من مكان). */
    private const BRAND_ORANGE = '#D47716'; // البرتقالي/الذهبي — اللون الأساسي

    private const BRAND_BROWN = '#3E1F15';  // البني الداكن — الخلفيات

    public function panel(Panel $panel): Panel
    {
        return $panel
            // ─────────────────────────────────────────────
            // 1) الإعدادات الأساسية للوحة
            // ─────────────────────────────────────────────
            ->default()                 // اللوحة الافتراضية للتطبيق
            ->id('admin')               // معرّف اللوحة (يُستخدم في أسماء الـ routes)
            ->path('admin')             // الرابط: yourdomain.com/admin
            ->login()                   // تفعيل صفحة تسجيل الدخول المدمجة

            // ─────────────────────────────────────────────
            // 2) الهوية البصرية (Branding)
            // ─────────────────────────────────────────────
            ->colors([
                'primary' => Color::hex(self::BRAND_ORANGE),
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                'danger' => Color::Rose,
                // لون مخصص إضافي يمكن استخدامه عبر ->color('brand') في المكوّنات
                'brand' => Color::hex(self::BRAND_BROWN),
            ])
            ->font('Tajawal')           // خط عربي واضح يناسب الطابع الأنيق
            ->brandName('المطبخ اليمني - Jemenitische Keuken')
            // الشعار: يظهر بنفس الصورة في الوضعين الفاتح والداكن.
            // نصيحة: استخدم نسخة PNG/SVG بخلفية شفافة (logo.png) لمظهر أنظف.
            ->brandLogo(asset('images/logo.jpg'))
            ->darkModeBrandLogo(asset('images/logo.jpg'))
            ->brandLogoHeight('3.5rem') // أكبر من الافتراضي مع بقائه ضمن ارتفاع الشريط العلوي
            ->favicon(asset('favicon.ico'))

            // ─────────────────────────────────────────────
            // 3) تجربة الاستخدام (UX)
            // ─────────────────────────────────────────────
            ->sidebarCollapsibleOnDesktop()                    // إمكانية طيّ القائمة الجانبية
            ->maxContentWidth('full')                          // استغلال كامل عرض الشاشة للجداول والرسوم
            ->unsavedChangesAlerts()                           // تنبيه عند مغادرة نموذج غير محفوظ
            ->globalSearchKeyBindings(['command+k', 'ctrl+k']) // اختصار البحث السريع

            // ─────────────────────────────────────────────
            // 4) اكتشاف المكوّنات تلقائيًا (Auto-discovery)
            //    أي ملف جديد داخل هذه المجلدات يظهر في اللوحة دون تسجيل يدوي
            // ─────────────────────────────────────────────
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class, // الصفحة الرئيسية للوحة
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            // لتفعيل ويدجت الحساب الافتراضي أعد إضافة:
            // ->widgets([Widgets\AccountWidget::class])

            // ─────────────────────────────────────────────
            // 5) الـ Middleware (الحماية ومعالجة الطلبات)
            // ─────────────────────────────────────────────
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class, // منع الدخول لغير المسجّلين
            ])

            // ─────────────────────────────────────────────
            // 6) الإضافات (Plugins)
            // ─────────────────────────────────────────────
            // دعم الترجمة للحقول: الإنجليزية، العربية، الهولندية
            ->plugin(
                SpatieTranslatablePlugin::make()->defaultLocales(['en', 'ar', 'nl'])
            )

            // ─────────────────────────────────────────────
            // 7) Render Hooks (حقن عناصر مخصصة في الواجهة)
            // ─────────────────────────────────────────────
            // تحسين مظهر الشعار: زوايا دائرية، ظل ذهبي خفيف، وحجم كبير في صفحة الدخول
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn () => new HtmlString('
                    <style>
                        /* الشعار في الشريط العلوي / القائمة الجانبية */
                        .fi-logo {
                            border-radius: 0.75rem;
                            box-shadow: 0 2px 10px rgba(212, 119, 22, 0.35);
                            transition: transform .25s ease, box-shadow .25s ease;
                        }
                        .fi-logo:hover {
                            transform: scale(1.04);
                            box-shadow: 0 4px 16px rgba(212, 119, 22, 0.55);
                        }
                        /* الشعار في صفحة تسجيل الدخول: أكبر وأبرز */
                        .fi-simple-layout .fi-logo {
                            height: 9rem !important;
                            border-radius: 1.25rem;
                            box-shadow: 0 8px 30px rgba(62, 31, 21, 0.45);
                            margin-bottom: 0.5rem;
                        }
                    </style>
                '),
            )

            // زر "عرض التطبيق" قبل قائمة المستخدم في الشريط العلوي
            // (لنقله إلى ترويسة الصفحات استخدم PAGE_HEADER_ACTIONS_AFTER)
            ->renderHook(
                PanelsRenderHook::USER_MENU_BEFORE,
                fn () => view('filament.admin-header.view-user-app-button'),
            );
    }
}
