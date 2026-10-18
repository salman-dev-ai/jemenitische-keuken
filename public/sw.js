/*
 * Service Worker لتطبيق المطبخ اليمني.
 *
 * الغرض:
 *   1. استيفاء شرط تثبيت التطبيق من المتصفح ("إضافة إلى الشاشة الرئيسية").
 *   2. تسريع تحميل الأصول الثابتة (CSS / JS / صور / خطوط).
 *
 * قواعد السلامة المتبعة:
 *   - لا يُعترض أي طلب غير GET أو أي طلب خارج نطاق الموقع.
 *   - تُستثنى مسارات Livewire و Filament (admin) كلياً لأنها ديناميكية وحساسة.
 *   - صفحات HTML تُجلب من الشبكة أولاً، فلا تظهر نسخة قديمة أثناء الاتصال.
 *   - ملفات الفيديو لا تُخزَّن لتجنب استهلاك حصة التخزين في الجهاز.
 *
 * ملاحظة عند النشر: عند تعديل ملفات الواجهة غيّر قيمة CACHE_VERSION
 * حتى يتخلص المتصفح من النسخة القديمة المخزنة.
 */

const CACHE_VERSION = 'v1';

const STATIC_CACHE = `jyk-static-${CACHE_VERSION}`;
const PAGE_CACHE = `jyk-pages-${CACHE_VERSION}`;

/** أصول أساسية تُخزَّن عند أول تثبيت. */
const PRECACHE_URLS = [
    '/manifest.json',
    '/icons/icon-192.png',
    '/icons/icon-512.png',
    '/icons/apple-touch-icon.png',
];

/** مسارات لا يمر عليها الـ Service Worker إطلاقاً. */
const BYPASS_PREFIXES = ['/livewire/', '/admin', '/up'];

/** امتدادات الأصول الثابتة القابلة للتخزين. */
const STATIC_ASSET_PATTERN = /\.(?:css|js|mjs|png|jpe?g|webp|avif|gif|svg|ico|woff2?|ttf|otf)$/i;

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches
            .open(STATIC_CACHE)
            .then((cache) => cache.addAll(PRECACHE_URLS))
            .then(() => self.skipWaiting()),
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((keys) =>
                Promise.all(
                    keys
                        .filter((key) => key !== STATIC_CACHE && key !== PAGE_CACHE)
                        .map((key) => caches.delete(key)),
                ),
            )
            .then(() => self.clients.claim()),
    );
});

self.addEventListener('fetch', (event) => {
    const { request } = event;

    if (request.method !== 'GET') {
        return;
    }

    const url = new URL(request.url);

    if (url.origin !== self.location.origin) {
        return;
    }

    if (BYPASS_PREFIXES.some((prefix) => url.pathname.startsWith(prefix))) {
        return;
    }

    if (request.mode === 'navigate') {
        event.respondWith(networkFirstPage(request));

        return;
    }

    if (STATIC_ASSET_PATTERN.test(url.pathname)) {
        event.respondWith(staleWhileRevalidate(request));
    }
});

/**
 * صفحات HTML: الشبكة أولاً، والنسخة المخزنة بديل عند انقطاع الاتصال فقط.
 */
async function networkFirstPage(request) {
    const cache = await caches.open(PAGE_CACHE);

    try {
        const response = await fetch(request);

        if (response.ok) {
            cache.put(request, response.clone());
        }

        return response;
    } catch {
        const cached = await cache.match(request);

        return cached || offlineFallback();
    }
}

/**
 * أصول ثابتة: النسخة المخزنة فوراً مع تحديثها في الخلفية.
 */
async function staleWhileRevalidate(request) {
    const cache = await caches.open(STATIC_CACHE);
    const cached = await cache.match(request);

    const network = fetch(request).then((response) => {
        if (response.ok) {
            cache.put(request, response.clone());
        }

        return response;
    });

    return cached || network;
}

/**
 * صفحة بديلة بسيطة تُعرض عند فشل الشبكة وعدم وجود نسخة مخزنة.
 */
function offlineFallback() {
    const html = [
        '<!DOCTYPE html>',
        '<html lang="ar" dir="rtl">',
        '<head><meta charset="utf-8">',
        '<meta name="viewport" content="width=device-width, initial-scale=1.0">',
        '<title>لا يوجد اتصال</title></head>',
        '<body style="margin:0;display:flex;min-height:100vh;align-items:center;justify-content:center;',
        'background:#FDFBF7;color:#2C1810;font-family:system-ui,sans-serif;text-align:center">',
        '<div><h1 style="font-size:1.25rem;margin-bottom:.5rem">لا يوجد اتصال بالإنترنت</h1>',
        '<p style="margin:0">تأكد من الشبكة ثم أعد المحاولة.</p></div>',
        '</body></html>',
    ].join('');

    return new Response(html, {
        status: 503,
        headers: { 'Content-Type': 'text/html; charset=utf-8' },
    });
}
