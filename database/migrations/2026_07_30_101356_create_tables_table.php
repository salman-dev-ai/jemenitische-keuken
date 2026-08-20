<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Responsibility: Represents individual tables in the restaurant for seat allocation and reservations.
     */
    public function up(): void

    {
        Schema::create('tables', function (Blueprint $table) {
            $table->id();

            $table->string('table_number')->unique(); // e.g. "T-01", "T-02"
            $table->integer('capacity'); // Guests count
            $table->string('location_zone')->nullable(); // e.g. "Family Section", "Main Hall"
            $table->boolean('is_available')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
