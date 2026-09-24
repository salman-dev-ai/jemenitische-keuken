<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. إضافة عمود reservation_id + Foreign Key + Index
        if (! Schema::hasColumn('orders', 'reservation_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreignId('reservation_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('reservations')
                    ->nullOnDelete()
                    ->cascadeOnUpdate();
            });
        }

        // 2. تعديل عمود type (MySQL/MariaDB فقط)
        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE orders MODIFY COLUMN type ENUM('pickup', 'dine_in', 'preorder') NOT NULL DEFAULT 'pickup'");
        }
    }

    public function down(): void
    {
        // 1. عكس تعديل type أولاً
        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE orders MODIFY COLUMN type ENUM('pickup', 'dine_in') NOT NULL DEFAULT 'pickup'");
        }

        // 2. حذف عمود reservation_id
        if (Schema::hasColumn('orders', 'reservation_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign(['reservation_id']);
                $table->dropColumn('reservation_id');
            });
        }
    }
};