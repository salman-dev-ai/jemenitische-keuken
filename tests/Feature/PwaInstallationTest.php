<?php

test('the root redirects to the default locale so the app is reachable', function () {
    // الوصول عبر الجذر يعمل بعد إعادة التوجيه إلى اللغة الافتراضية (/nl).
    $this->get('/')->assertRedirect('/nl');
    $this->get('/nl')->assertOk();
});
