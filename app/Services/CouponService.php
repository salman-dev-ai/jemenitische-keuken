<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\CouponResult;
use App\Enums\CouponScope;
use App\Enums\CouponUsageStatus;
use App\Exceptions\CouponException;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * خدمة الكوبونات — القلب الحقيقي لقسم التخفيضات.
 *
 * المسؤوليات:
 *  1. التحقق من صلاحية الكوبون (validate)
 *  2. حساب قيمة الخصم حسب نطاق الكوبون (calculateDiscount)
 *  3. حفظ الكوبون في حساب العميل (claim)
 *  4. تعليمه كمُستخدم عند تأكيد الطلب (redeem)
 *  5. تحريره عند إلغاء الطلب (release)
 *
 * تصميم متوافق مع Race Conditions:
 *  - redeem يستخدم DB::transaction + lockForUpdate
 *  - increment usage يتم atomically
 */
class CouponService
{
    /**
     * التحقق الكامل من الكوبون بناءً على السلة والعميل.
     *
     * @param string               $code     كود الكوبون
     * @param Customer             $customer العميل
     * @param array<int, array{id:int, price:float, quantity:int, category_id:?int}> $items عناصر السلة
     */
    public function validate(string $code, Customer $customer, array $items): CouponResult
    {
        try {
            $coupon = $this->findByCode($code);

            $this->ensureIsActive($coupon);
            $this->ensureNotExpired($coupon);
            $this->ensureNotExhausted($coupon);
            $this->ensureCustomerUnderLimit($coupon, $customer);

            // حساب الإجمالي الكامل
            $orderTotal = $this->calculateItemsTotal($items);

            // المبلغ المؤهل للخصم حسب نطاق الكوبون
            $eligibleTotal = $this->calculateEligibleTotal($coupon, $items);

            if ($eligibleTotal <= 0.0 && $coupon->applies_to !== CouponScope::All) {
                throw CouponException::notApplicable();
            }

            $this->ensureMeetsMinimum($coupon, $orderTotal);

            // حساب الخصم
            $discount = $this->calculateDiscount($coupon, $orderTotal, $eligibleTotal);

            return CouponResult::success($coupon, $discount);

        } catch (CouponException $e) {
            return CouponResult::failure($e->errorCode, $e->getMessage());
        }
    }

    /**
     * حفظ الكوبون في حساب العميل (بعد الضغط على "احصل على الكوبون").
     * Idempotent: إن كان محفوظًا مسبقًا بحالة claimed، نعيده بدون إنشاء جديد.
     */
    public function claim(Customer $customer, Coupon $coupon): CouponUsage
    {
        return DB::transaction(function () use ($customer, $coupon): CouponUsage {
            // فحص سريع لصلاحية الكوبون
            $coupon = Coupon::query()
                ->whereKey($coupon->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if (! $coupon->isValid()) {
                throw $coupon->isExpired()
                    ? CouponException::expired()
                    : CouponException::exhausted();
            }

            // هل العميل حصل عليه مسبقًا؟
            $existing = CouponUsage::query()
                ->where('coupon_id', $coupon->id)
                ->where('customer_id', $customer->id)
                ->where('status', CouponUsageStatus::Claimed)
                ->first();

            if ($existing !== null) {
                return $existing;
            }

            // هل تجاوز الحد لكل عميل؟
            $this->ensureCustomerUnderLimit($coupon, $customer);

            return CouponUsage::create([
                'coupon_id' => $coupon->id,
                'customer_id' => $customer->id,
                'status' => CouponUsageStatus::Claimed,
                'claimed_at' => now(),
            ]);
        });
    }

    /**
     * تعليم الكوبون كمُستخدم عند تأكيد الطلب.
     * atomic: increment used_count + تحديث حالة الاستخدام.
     */
    public function redeem(CouponUsage $usage, Order $order, float $discountAmount): void
    {
        DB::transaction(function () use ($usage, $order, $discountAmount): void {
            $fresh = CouponUsage::query()
                ->whereKey($usage->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if (! $fresh->isUsable()) {
                throw CouponException::alreadyUsed();
            }

            $coupon = Coupon::query()
                ->whereKey($fresh->coupon_id)
                ->lockForUpdate()
                ->firstOrFail();

            // منع تجاوز الحد الكلي (بعد القفل)
            if ($coupon->max_uses !== null && $coupon->used_count >= $coupon->max_uses) {
                throw CouponException::exhausted();
            }

            $fresh->markAsUsed($order->id, $discountAmount);
            $coupon->incrementUsage();
        });
    }

    /**
     * تحرير الكوبون عند إلغاء الطلب.
     */
    public function release(CouponUsage $usage): void
    {
        DB::transaction(function () use ($usage): void {
            $fresh = CouponUsage::query()
                ->whereKey($usage->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($fresh->status !== CouponUsageStatus::Used) {
                return;
            }

            $coupon = Coupon::query()
                ->whereKey($fresh->coupon_id)
                ->lockForUpdate()
                ->firstOrFail();

            $fresh->release();

            if ($coupon->used_count > 0) {
                $coupon->decrement('used_count');
            }
        });
    }

    // ══════════════════════════════════════════════════════════════
    // Private Helpers
    // ══════════════════════════════════════════════════════════════

    private function findByCode(string $code): Coupon
    {
        $coupon = Coupon::query()
            ->whereRaw('LOWER(code) = ?', [mb_strtolower(trim($code))])
            ->first();

        if ($coupon === null) {
            throw CouponException::notFound($code);
        }

        return $coupon;
    }

    private function ensureIsActive(Coupon $coupon): void
    {
        if (! $coupon->is_active) {
            throw CouponException::inactive();
        }
    }

    private function ensureNotExpired(Coupon $coupon): void
    {
        if ($coupon->isExpired()) {
            throw CouponException::expired();
        }
    }

    private function ensureNotExhausted(Coupon $coupon): void
    {
        if ($coupon->hasReachedMaxUses()) {
            throw CouponException::exhausted();
        }
    }

    private function ensureCustomerUnderLimit(Coupon $coupon, Customer $customer): void
    {
        if ($coupon->max_uses_per_customer === null) {
            return;
        }

        $usedByCustomer = CouponUsage::query()
            ->where('coupon_id', $coupon->id)
            ->where('customer_id', $customer->id)
            ->whereIn('status', [
                CouponUsageStatus::Claimed,
                CouponUsageStatus::Used,
            ])
            ->count();

        if ($usedByCustomer >= $coupon->max_uses_per_customer) {
            throw CouponException::customerLimit($coupon->max_uses_per_customer);
        }
    }

    private function ensureMeetsMinimum(Coupon $coupon, float $orderTotal): void
    {
        if (! $coupon->meetsMinimumTotal($orderTotal)) {
            throw CouponException::minTotal(
                (float) $coupon->min_order_total,
                $orderTotal,
            );
        }
    }

    /**
     * @param array<int, array{id:int, price:float, quantity:int, category_id:?int}> $items
     */
    private function calculateItemsTotal(array $items): float
    {
        $total = 0.0;

        foreach ($items as $item) {
            $total += (float) $item['price'] * (int) $item['quantity'];
        }

        return round($total, 2);
    }

    /**
     * حساب المبلغ المؤهل للخصم حسب نطاق الكوبون.
     *
     * @param array<int, array{id:int, price:float, quantity:int, category_id:?int}> $items
     */
    private function calculateEligibleTotal(Coupon $coupon, array $items): float
    {
        if ($coupon->applies_to === CouponScope::All) {
            return $this->calculateItemsTotal($items);
        }

        $eligibleIds = match ($coupon->applies_to) {
            CouponScope::Products => $coupon->menuItems()->pluck('menu_items.id')->all(),
            CouponScope::Categories => $this->resolveCategoryItemIds($coupon),
            default => [],
        };

        if ($eligibleIds === []) {
            return 0.0;
        }

        $total = 0.0;

        foreach ($items as $item) {
            $matches = $coupon->applies_to === CouponScope::Products
                ? in_array($item['id'], $eligibleIds, true)
                : in_array($item['category_id'], $eligibleIds, true);

            if ($matches) {
                $total += (float) $item['price'] * (int) $item['quantity'];
            }
        }

        return round($total, 2);
    }

    /**
     * جلب IDs المنتجات ضمن تصنيفات الكوبون.
     *
     * @return array<int, int>
     */
    private function resolveCategoryItemIds(Coupon $coupon): array
    {
        $categoryIds = $coupon->menuCategories()->pluck('menu_categories.id')->all();

        if ($categoryIds === []) {
            return [];
        }

        return \App\Models\MenuItem::query()
            ->whereIn('menu_category_id', $categoryIds)
            ->pluck('id')
            ->all();
    }

    /**
     * حساب قيمة الخصم.
     */
    private function calculateDiscount(
        Coupon $coupon,
        float $orderTotal,
        float $eligibleTotal,
    ): float {
        $base = $coupon->applies_to === CouponScope::All ? $orderTotal : $eligibleTotal;

        if ($base <= 0.0) {
            return 0.0;
        }

        $discount = $coupon->discount_type->apply($base, (float) $coupon->discount_value);

        return round(min($discount, $base), 2);
    }
}