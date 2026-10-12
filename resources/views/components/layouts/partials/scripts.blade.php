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

{{-- سكربتات دُفعت من الصفحات عبر stack('scripts') --}}
@stack('scripts')
