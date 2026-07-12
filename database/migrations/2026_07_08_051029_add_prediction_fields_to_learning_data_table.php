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
            $table->string('recommendation_result')->nullable()->after('catatan');
            $table->double('confidence')->nullable()->after('recommendation_result');
            $table->timestamp('prediction_time')->nullable()->after('confidence');
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
