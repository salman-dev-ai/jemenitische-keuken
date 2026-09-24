<?php

declare(strict_types=1);

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * 📄 المسار: app/Exceptions/OrderException.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 الغرض:
 *    استثناءات مخصصة لنظام الطلبات. تُستخدم في OrderService و MenuWithCart
 *    للتمييز بين أخطاء التحقق المتوقعة والأخطاء غير المتوقعة.
 *
 * 🧩 يعتمد على:
 *    - lang/{ar,en,nl}/messages.php → قسم errors.*
 *
 * 📌 طريقة الاستخدام:
 *    throw OrderException::emptyCart();
 *    throw OrderException::invalidQuantity('شاورما', 999);
 *
 * 🎯 في try/catch:
 *    catch (OrderException $e) {
 *        // خطأ متوقع — اعرضه للمستخدم دون report()
 *    } catch (\Exception $e) {
 *        // خطأ غير متوقع — report($e)
 *    }
 *
 * 🕒 آخر تحديث: 2026-09-24
 * ═══════════════════════════════════════════════════════════════════════════
 */

namespace App\Exceptions;

use Exception;

class OrderException extends Exception
{
    /** 🛒 السلة فارغة */
    public static function emptyCart(): self
    {
        return new self(__('messages.errors.empty_cart'));
    }

    /** 🍽️ طبق غير متوفر (معرّفه في الرسالة) */
    public static function menuItemUnavailable(int $id): self
    {
        return new self(__('messages.errors.menu_item_unavailable', ['id' => $id]));
    }

    /** 🔢 كمية غير صالحة لطبق معيّن */
    public static function invalidQuantity(string $name, int $qty): self
    {
        return new self(__('messages.errors.invalid_quantity', [
            'name' => $name,
            'qty'  => $qty,
        ]));
    }

    /** 🆔 معرّف طبق غير صالح (0 أو سالب) */
    public static function invalidMenuItemId(): self
    {
        return new self(__('messages.errors.invalid_menu_item_id'));
    }

    /** 🔗 حجز مرتبط غير موجود */
    public static function invalidReservation(): self
    {
        return new self(__('messages.errors.invalid_reservation'));
    }

    /** 📌 حالة طلب غير صالحة */
    public static function invalidStatus(): self
    {
        return new self(__('messages.errors.invalid_order_status'));
    }

    /** 📌 نوع طلب غير صالح */
    public static function invalidType(): self
    {
        return new self(__('messages.errors.invalid_order_type'));
    }
}