<?php

/*
|--------------------------------------------------------------------------
| اختبارات تطبيق الويب التقدمي (PWA)
|--------------------------------------------------------------------------
|
| تتحقق من أن الصفحة العامة تُعلن عن ملف manifest وأيقونات التثبيت،
| وأن الأصول المشار إليها موجودة فعلاً حتى لا يفشل زر "إضافة إلى الشاشة الرئيسية".
|
*/

test('الصفحة الرئيسية تُعلن عن إعدادات التثبيت', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('rel="manifest"', false)
        ->assertSee('manifest.json', false)
        ->assertSee('theme-color', false)
        ->assertSee('apple-touch-icon.png', false)
        ->assertSee('/sw.js', false);
});

test('ملف manifest صالح ويشير إلى أيقونات موجودة', function () {
    $path = public_path('manifest.json');

    expect($path)->toBeFile();

    $manifest = json_decode((string) file_get_contents($path), true);

    expect($manifest)
        ->toBeArray()
        ->and($manifest['start_url'])->toBe('/')
        ->and($manifest['scope'])->toBe('/')
        ->and($manifest['display'])->toBe('standalone')
        ->and($manifest['icons'])->not->toBeEmpty();

    $sizes = [];

    foreach ($manifest['icons'] as $icon) {
        $sizes[] = $icon['sizes'];

        expect(public_path(ltrim($icon['src'], '/')))->toBeFile();
    }

    expect($sizes)->toContain('192x192')->toContain('512x512');
});

test('Service Worker يستثني مسارات Livewire و Filament', function () {
    $path = public_path('sw.js');

    expect($path)->toBeFile();

    $script = (string) file_get_contents($path);

    expect($script)
        ->toContain("addEventListener('fetch'")
        ->toContain("'/livewire/'")
        ->toContain("'/admin'");
});
