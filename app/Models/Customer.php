<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * الحقول التي يمكن تعبئتها جماعياً.
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
     * الحقول التي يجب أن تكون مخفية افتراضياً.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * الحقول التي يجب تحويلها إلى أنواع أصلية.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_order_at' => 'datetime',
    ];

    /**
     * الحصول على الحجوزات المرتبطة بالعميل.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * الحصول على الطلبات المرتبطة بالعميل.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}