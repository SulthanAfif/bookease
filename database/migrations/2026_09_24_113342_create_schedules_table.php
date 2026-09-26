<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('schedules', function (Blueprint $table) {
        $table->id();
        $table->foreignId('staff_id')->constrained()->cascadeOnDelete();
        $table->unsignedTinyInteger('day_of_week'); // 0 = Minggu, 1 = Senin, dst
        $table->time('start_time');
        $table->time('end_time');
        $table->boolean('is_day_off')->default(false);
        $table->timestamps();

        $table->unique(['staff_id', 'day_of_week']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
