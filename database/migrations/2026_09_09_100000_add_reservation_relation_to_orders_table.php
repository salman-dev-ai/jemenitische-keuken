<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('orders', 'reservation_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreignId('reservation_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('reservations')
                    ->nullOnDelete()
                    ->cascadeOnUpdate();

                $table->index('reservation_id');
            });
        }

        $driver = DB::getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN type ENUM('pickup', 'dine_in', 'preorder') NOT NULL DEFAULT 'pickup'");
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'reservation_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign(['reservation_id']);
                $table->dropIndex(['reservation_id']);
                $table->dropColumn('reservation_id');
            });
        }

        $driver = DB::getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN type ENUM('pickup', 'dine_in') NOT NULL DEFAULT 'pickup'");
        }
    }
};
