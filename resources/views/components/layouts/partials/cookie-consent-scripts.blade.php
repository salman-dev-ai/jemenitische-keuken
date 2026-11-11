{{-- ضعه داخل <head> قبل أي سكربت تتبع. يحتاج: <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
<script>
    (function () {
        'use strict';

        const CATEGORIES = ['analytics', 'marketing'];
        const PURGE = @json(config('cookies.purge'));
        const state = Object.assign(
            { decided: false, necessary: true, analytics: false, marketing: false },
            @json($cookieConsent)
        );
        const queue = [];

        function expireCookie(name) {
            const parts = location.hostname.split('.');
            const domains = [null];
            for (let i = 0; i < parts.length - 1; i++) {
                domains.push('.' + parts.slice(i).join('.'));
            }
            domains.forEach(function (domain) {
                document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/' +
                    (domain ? '; domain=' + domain : '');
            });
        }

        function purge(category) {
            const patterns = PURGE[category] || [];
            document.cookie.split(';')
                .map(function (c) { return c.split('=')[0].trim(); })
                .filter(Boolean)
                .forEach(function (name) {
                    const matches = patterns.some(function (p) {
                        return p.endsWith('*') ? name.startsWith(p.slice(0, -1)) : name === p;
                    });
                    if (matches) expireCookie(name);
                });
        }

        const api = {
            state: state,

            allows: function (category) {
                return category === 'necessary' || state[category] === true;
            },

            // سجّل محمِّل سكربت يعمل مرة واحدة فقط وبعد الموافقة على فئته
            register: function (category, loader) {
                if (this.allows(category)) {
                    loader();
                } else {
                    queue.push({ category: category, loader: loader });
                }
            },

            update: function (next) {
                const revoked = CATEGORIES.filter(function (c) { return state[c] && !next[c]; });

                Object.assign(state, {
                    decided: true,
                    analytics: !!next.analytics,
                    marketing: !!next.marketing,
                });

                if (revoked.length) {
                    // سحب الموافقة: احذف الكوكيز وأعد التحميل لإيقاف السكربتات الجارية
                    revoked.forEach(purge);
                    location.reload();
                    return;
                }

                for (let i = queue.length - 1; i >= 0; i--) {
                    if (this.allows(queue[i].category)) {
                        const item = queue.splice(i, 1)[0];
                        item.loader();
                    }
                }

                window.dispatchEvent(new CustomEvent('cookie-consent-changed', { detail: state }));
            },

            open: function () {
                window.dispatchEvent(new CustomEvent('cookie-consent-open'));
            },
        };

        window.CookieConsent = api;

        // ═══════ سكربتات مُقيَّدة: لا تُحمَّل إلا بعد الموافقة على تصنيفها ═══════

        // 📊 Google Analytics 4 — يُحمَّل فقط عند الموافقة على التحليلات.
        api.register('analytics', function () {
            const id = window.__analyticsId;
            if (!id) return;

            // Google Consent Mode v2: نبدأ بالرفض ثم نمنح التخزين التحليلي بعد الموافقة.
            window.dataLayer = window.dataLayer || [];
            function gtag() { window.dataLayer.push(arguments); }
            window.gtag = gtag;
            gtag('consent', 'default', {
                ad_storage: 'denied',
                ad_user_data: 'denied',
                ad_personalization: 'denied',
                analytics_storage: 'denied',
            });
            gtag('consent', 'update', { analytics_storage: 'granted' });
            gtag('js', new Date());
            gtag('config', id, { anonymize_ip: true });

            const s = document.createElement('script');
            s.async = true;
            s.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(id);
            document.head.appendChild(s);
        });

        // 🎯 Meta Pixel — يُحمَّل فقط عند الموافقة على التسويق.
        api.register('marketing', function () {
            const id = window.__metaPixelId;
            if (!id) return;

            !function (f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function () {
                    n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
                };
                if (!f._fbq) f._fbq = n;
                n.push = n; n.loaded = true; n.version = '2.0'; n.queue = [];
                t = b.createElement(e); t.async = true; t.src = v;
                s = b.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t, s);
            }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');

            window.fbq('init', id);
            window.fbq('track', 'PageView');
        });
    })();
</script>