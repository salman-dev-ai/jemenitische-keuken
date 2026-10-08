<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * نموذج العميل.
 *
 * 🔑 المعرّف الوحيد: phone (مُطبَّع) — لا email.
 * 🍪 remember_token: يُخزَّن في كوكي + DB لتذكّر العميل.
 * 🆕 isNew(): عميل جديد (لم يطلب بعد) → يستحق كوبون ترحيبي.
 */
class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * الحقول القابلة للتعيين الجماعي.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'remember_token',
        'last_order_at',
    ];

    /**
     * الحقول المخفية عند التحويل إلى array/JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * تحويلات الأنواع — Laravel 11+ style.
     *
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'last_order_at' => 'datetime',
        ];
    }

    // ══════════════════════════════════════════════════════════════
    // العلاقات
    // ══════════════════════════════════════════════════════════════

    /**
     * حجوزات العميل.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * طلبات العميل.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * سجل استخدامات الكوبونات (لقسم "كوبوناتي").
     */
    public function couponUsages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    // ══════════════════════════════════════════════════════════════
    // Scopes
    // ══════════════════════════════════════════════════════════════

    /**
     * العملاء الجدد (لم يطلبوا بعد) — مؤهلون للكوبون الترحيبي.
     */
    public function scopeNew(Builder $query): Builder
    {
        return $query->whereNull('last_order_at');
    }

    /**
     * العملاء العائدون (طلبوا سابقاً).
     */
    public function scopeReturning(Builder $query): Builder
    {
        return $query->whereNotNull('last_order_at');
    }

    // ══════════════════════════════════════════════════════════════
    // Business Helpers
    // ══════════════════════════════════════════════════════════════

    /**
     * هل العميل طلب سابقاً؟
     */
    public function hasEverOrdered(): bool
    {
        return $this->last_order_at !== null;
    }

    /**
     * هل العميل جديد (لم يطلب بعد)؟
     *
     * يُستخدم لمنطق الكوبون الترحيبي:
     *   - new  → يمكنه رؤية/المطالبة بالكوبون الترحيبي
     *   - not  → يرى العروض العادية فقط
     */
    public function isNew(): bool
    {
        return ! $this->hasEverOrdered();
    }
}
