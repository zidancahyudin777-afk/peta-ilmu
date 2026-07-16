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
        Schema::table('learning_data', function (Blueprint $table) {
            $table->enum('recommendation_result', ['Dasar', 'Menengah', 'Mahir', 'Program Remedial Intensif', 'Pendampingan Akademik', 'Program Reguler', 'Program Pengayaan', 'Menunggu Input Siswa'])->default('Menunggu Input Siswa')->after('catatan');
            $table->decimal('confidence', 5, 2)->nullable()->after('recommendation_result');
            $table->dateTime('prediction_time')->nullable()->after('confidence');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learning_data', function (Blueprint $table) {
            $table->dropColumn(['recommendation_result', 'confidence', 'prediction_time']);
        });
    }
};
