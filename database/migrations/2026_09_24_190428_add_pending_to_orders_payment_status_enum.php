<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ─────────────────────────────────────────────────────────────
        // إضافة 'pending' إلى ENUM عمود payment_status
        // ─────────────────────────────────────────────────────────────
        // ⚠️ يجب أن يحتوي التعريف الجديد على كل القيم الحالية + 'pending'
        //    حتى لا نفقد أي قيمة موجودة مسبقاً.
        //    القيم الحالية المتوقعة: 'unpaid', 'paid', 'refunded'
        //    (راجع تعريف العمود الحالي قبل التنفيذ)
        // ─────────────────────────────────────────────────────────────
        DB::statement("
            ALTER TABLE `orders`
            MODIFY COLUMN `payment_status`
            ENUM('pending', 'unpaid', 'paid', 'failed', 'refunded')
            NOT NULL DEFAULT 'pending'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إعادة التعريف القديم (احذف 'pending' من القائمة)
        DB::statement("
            ALTER TABLE `orders`
            MODIFY COLUMN `payment_status`
            ENUM('unpaid', 'paid', 'failed', 'refunded')
            NOT NULL DEFAULT 'unpaid'
        ");
    }
};