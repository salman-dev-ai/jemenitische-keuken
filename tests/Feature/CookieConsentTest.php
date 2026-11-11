<?php

use App\Filament\Resources\CookieConsents\CookieConsentResource;
use App\Models\CookieConsent;

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * 📄 المسار: tests/Feature/CookieConsentTest.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 الغرض:
 *    التحقق من منطق الموافقة على الكوكيز (GDPR):
 *    - حفظ القرار خادمياً (تحقق صارم) + إثبات الموافقة في قاعدة البيانات.
 *    - إصدار كوكي الطرف الأول الآمنة.
 *    - حَقن الحالة في الواجهات عبر Middleware مع رفض النسخ القديمة.
 *    - بقاء مورد Filament للقراءة فقط.
 *
 * 🕒 آخر تحديث: 2026-11-10
 * ═══════════════════════════════════════════════════════════════════════════
 */
it('يحفظ الموافقة ويعيد كوكي آمنة ويسجّل الإثبات في قاعدة البيانات', function () {
    $response = $this->postJson(route('cookie-consent.store'), [
        'analytics' => true,
        'marketing' => true,
    ]);

    $response->assertOk()
        ->assertJson(['ok' => true])
        ->assertCookie(config('cookies.cookie_name'));

    // صف إثبات واحد فقط مطابق للقرار.
    expect(CookieConsent::query()->count())->toBe(1);

    $consent = CookieConsent::query()->firstOrFail();

    expect($consent->necessary)->toBeTrue()
        ->and($consent->analytics)->toBeTrue()
        ->and($consent->marketing)->toBeTrue()
        ->and($consent->version)->toBe((int) config('cookies.version'))
        // بصمة IP مجزأة: ليست العنوان الخام ولا فارغة.
        ->and($consent->ip_hash)->not->toBeNull()
        ->and($consent->ip_hash)->not->toBe('127.0.0.1');
});

it('يحفظ الرفض الكامل عند اختيار الضرورية فقط', function () {
    $this->postJson(route('cookie-consent.store'), [
        'analytics' => false,
        'marketing' => false,
    ])->assertOk();

    $consent = CookieConsent::query()->firstOrFail();

    expect($consent->analytics)->toBeFalse()
        ->and($consent->marketing)->toBeFalse()
        ->and($consent->necessary)->toBeTrue();
});

it('يرفض المدخلات غير المنطقية ولا يسجّل شيئاً (تحقق خادمي)', function () {
    $this->postJson(route('cookie-consent.store'), [
        'analytics' => 'maybe',
        'marketing' => true,
    ])->assertStatus(422);

    expect(CookieConsent::query()->count())->toBe(0);
});

it('يرفض الطلب عند غياب الحقول الإلزامية', function () {
    $this->postJson(route('cookie-consent.store'), [])
        ->assertStatus(422);

    expect(CookieConsent::query()->count())->toBe(0);
});

it('يعيد استخدام معرّف الزائر نفسه ويحدّث السجل بدل إنشاء صف جديد', function () {
    // طلبات JSON لا ترسل الكوكيز افتراضياً في بيئة الاختبار.
    $this->withCredentials();

    $uid = '11111111-1111-1111-1111-111111111111';

    $cookie = json_encode([
        'v' => config('cookies.version'),
        'necessary' => true,
        'analytics' => false,
        'marketing' => false,
        'ts' => now()->timestamp,
        'uid' => $uid,
    ]);

    $this->withCookie(config('cookies.cookie_name'), $cookie)
        ->postJson(route('cookie-consent.store'), ['analytics' => true, 'marketing' => false])
        ->assertOk();

    $this->withCookie(config('cookies.cookie_name'), $cookie)
        ->postJson(route('cookie-consent.store'), ['analytics' => false, 'marketing' => true])
        ->assertOk();

    expect(CookieConsent::query()->count())->toBe(1);

    $consent = CookieConsent::query()->firstOrFail();

    expect($consent->uuid)->toBe($uid)
        ->and($consent->analytics)->toBeFalse()
        ->and($consent->marketing)->toBeTrue();
});

it('يحقن حالة الموافقة المقبولة في الواجهة عبر الـ Middleware', function () {
    $this->withoutVite();

    $cookie = json_encode([
        'v' => config('cookies.version'),
        'necessary' => true,
        'analytics' => true,
        'marketing' => false,
        'ts' => now()->timestamp,
        'uid' => '22222222-2222-2222-2222-222222222222',
    ]);

    $response = $this->withCookie(config('cookies.cookie_name'), $cookie)
        ->get(route('restaurant-info'));

    $response->assertOk()
        ->assertSee('"decided":true', false)
        ->assertSee('"analytics":true', false)
        ->assertSee('"marketing":false', false);
});

it('يتجاهل الكوكي ذات نسخة سياسة قديمة ويعيد طلب الموافقة', function () {
    $this->withoutVite();

    $oldCookie = json_encode([
        'v' => ((int) config('cookies.version')) - 1,
        'necessary' => true,
        'analytics' => true,
        'marketing' => true,
        'ts' => now()->timestamp,
        'uid' => '33333333-3333-3333-3333-333333333333',
    ]);

    $this->withCookie(config('cookies.cookie_name'), $oldCookie)
        ->get(route('restaurant-info'))
        ->assertOk()
        ->assertSee('"decided":false', false)
        ->assertSee('"analytics":false', false);
});

it('يبقي مورد سجل الموافقات في لوحة التحكم للقراءة فقط', function () {
    $consent = CookieConsent::create([
        'uuid' => '44444444-4444-4444-4444-444444444444',
        'necessary' => true,
        'analytics' => true,
        'marketing' => false,
        'version' => (int) config('cookies.version'),
    ]);

    expect(CookieConsentResource::canCreate())->toBeFalse()
        ->and(CookieConsentResource::canEdit($consent))->toBeFalse()
        ->and(CookieConsentResource::canDelete($consent))->toBeFalse()
        ->and(CookieConsentResource::canDeleteAny())->toBeFalse();
});
