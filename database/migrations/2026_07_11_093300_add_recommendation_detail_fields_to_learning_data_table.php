<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan field rekomendasi lengkap ke tabel learning_data.
     * Field lama tidak diubah.
     */
    public function up(): void
    {
        Schema::table('learning_data', function (Blueprint $table) {
            $table->string('study_frequency', 30)->nullable()->after('prediction_time');
            $table->decimal('study_duration', 3, 1)->nullable()->after('study_frequency');
            $table->string('priority', 20)->nullable()->after('study_duration');
            $table->text('recommended_material')->nullable()->after('priority');
            $table->text('study_suggestion')->nullable()->after('recommended_material');
            $table->dateTime('prediction_date')->nullable()->after('study_suggestion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learning_data', function (Blueprint $table) {
            $table->dropColumn([
                'study_frequency',
                'study_duration',
                'priority',
                'recommended_material',
                'study_suggestion',
                'prediction_date',
            ]);
        });
    }
};
