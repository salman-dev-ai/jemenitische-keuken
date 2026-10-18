{{--
    سكربتات نهاية الصفحة.
    ترسم أيقونات Lucide بعد التحميل وبعد تنقل Livewire،
    ثم تخرج النوافذ المكدسة ومكدس السكربتات قبل سكربت الإطار.
--}}
{{-- النوافذ المنبثقة المسجلة في التخطيط --}}
@livewire('coupon-modal')
@livewire('video-modal')

@livewireScripts

{{-- إعادة رسم الأيقونات عند أول تحميل وبعد wire:navigate --}}
@once
    <script>
        (function () {
            function initLucideIcons() {
                if (window.lucide) window.lucide.createIcons();
            }

            document.addEventListener('DOMContentLoaded', initLucideIcons);
            document.addEventListener('livewire:navigated', initLucideIcons);
        })();
    </script>
@endonce

{{-- تسجيل Service Worker لتشغيل التثبيت على الشاشة الرئيسية --}}
@once
    <script>
        (function () {
            if (!('serviceWorker' in navigator) || window.__jemenitischeKeukenServiceWorker) return;

            window.__jemenitischeKeukenServiceWorker = true;

            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/sw.js', { scope: '/' }).catch(function () {
                    // يفشل التسجيل على نطاق بلا HTTPS، والموقع يعمل طبيعياً بدونه.
                });
            });
        })();
    </script>
@endonce

{{-- سكربتات دُفعت من الصفحات عبر stack('scripts') --}}
@stack('scripts')
