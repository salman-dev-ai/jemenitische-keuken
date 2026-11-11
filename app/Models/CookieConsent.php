<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * 📄 المسار: app/Models/CookieConsent.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 الغرض:
 *    سجل الموافقة على الكوكيز (إثبات الموافقة وفق GDPR).
 *    يُكتب من CookieConsentController@store ويُعرض قراءةً فقط في لوحة التحكم.
 *
 * 🕒 آخر تحديث: 2026-11-10
 * ═══════════════════════════════════════════════════════════════════════════
 */
class CookieConsent extends Model
{
    /**
     * الحقول القابلة للتعيين الجماعي.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'necessary',
        'analytics',
        'marketing',
        'version',
        'ip_hash',
        'user_agent',
    ];

    /**
     * تحويلات الأنواع.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'necessary' => 'boolean',
            'analytics' => 'boolean',
            'marketing' => 'boolean',
            'version' => 'integer',
        ];
    }
}
