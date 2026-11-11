<?php

declare(strict_types=1);

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * 📄 المسار: app/Http/Controllers/RobotsController.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 الغرض:
 *    تقديم ملف robots.txt ديناميكياً حتى يحتوي رابط خريطة الموقع المطلق
 *    الصحيح (المعتمد على APP_URL) دون كتابته يدوياً في ملف ثابت.
 *
 * 🔐 المنع: صفحات لوحة التحكم ومسارات Livewire وتبديل اللغة لا تُفهرس.
 * ═══════════════════════════════════════════════════════════════════════════
 */

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function __invoke(): Response
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',        // لوحة التحكم (Filament)
            'Disallow: /livewire',     // نقاط الاتصال الداخلية لـ Livewire
            'Disallow: /lang/switch',  // روابط تبديل اللغة (محتوى مكرر)
            '',
            // رابط مطلق لخريطة الموقع كما تشترطه محركات البحث.
            'Sitemap: '.url('sitemap.xml'),
        ];

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
