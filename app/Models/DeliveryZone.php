<?php

/**
 * موديل DeliveryZone
 *
 * وظيفة الملف:
 * - يمثل جدول delivery_zones في قاعدة البيانات.
 * - يحدد الحقول القابلة للتعبئة الجماعية (Mass Assignment).
 * - يحدد طريقة تحويل بعض الحقول عند القراءة والكتابة (Casts).
 *
 * ملاحظات:
 * - استخدام declare(strict_types=1) لفرض أنواع صارمة وتقليل الأخطاء.
 * - استخدام دالة casts() بدلًا من الخاصية $casts (الطريقة الحديثة في Laravel 12).
 * - delivery_cost يتم إرجاعه دائمًا بمنزلتين عشريتين.
 */

declare(strict_types=1);                              // فرض أنواع صارمة على مستوى الملف

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id                                 // المعرف
 * @property string $region_name                     // اسم المنطقة
 * @property string $postal_code                     // الرمز البريدي
 * @property string $delivery_cost                   // تكلفة التوصيل
 * @property Carbon|null $created_at                 // تاريخ الإنشاء
 * @property Carbon|null $updated_at                 // تاريخ التحديث
 */
class DeliveryZone extends Model
{
    use HasFactory;                                   // تفعيل المصانع (Factories) للاختبارات والـ Seeding

    /**
     * الحقول القابلة للتعبئة الجماعية.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'region_name',                                // اسم المنطقة
        'postal_code',                                // الرمز البريدي
        'delivery_cost',                              // تكلفة التوصيل
    ];

    /**
     * تحويل أنواع الحقول عند القراءة والكتابة.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'delivery_cost' => 'decimal:2',           // إرجاع التكلفة بمنزلتين عشريتين
        ];
    }
}