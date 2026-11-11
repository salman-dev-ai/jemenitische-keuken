<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // تعديل ENUM مدعوم في MySQL/MariaDB فقط؛ نتجاوز في بيئات الاختبار (SQLite).
        if (! in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

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
        if (! in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        // إعادة التعريف القديم (احذف 'pending' من القائمة)
        DB::statement("
            ALTER TABLE `orders`
            MODIFY COLUMN `payment_status`
            ENUM('unpaid', 'paid', 'failed', 'refunded')
            NOT NULL DEFAULT 'unpaid'
        ");
    }
};
