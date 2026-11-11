<?php

test('the root redirects to the default locale', function () {
    // الجذر "/" يُحوَّل دائماً إلى اللغة الافتراضية (nl) لمنع المحتوى المكرر.
    $this->get('/')->assertRedirect('/nl');
});
