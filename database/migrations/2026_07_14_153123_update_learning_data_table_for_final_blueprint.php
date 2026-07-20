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
            
            
            $table->unsignedBigInteger('program_id')->nullable()->after('user_id');
            $table->unsignedTinyInteger('nilai_tugas')->nullable()->after('nilai');
            $table->unsignedTinyInteger('nilai_kuis')->nullable()->after('nilai_tugas');
            $table->enum('kehadiran', ['Kurang', 'Cukup', 'Baik'])->default('Baik')->after('nilai_kuis');

            
            $table->dropColumn('gaya_belajar');

            
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learning_data', function (Blueprint $table) {
            
            $table->dropForeign(['program_id']);
            $table->dropColumn('program_id');

            
            $table->dropColumn(['nilai_tugas', 'nilai_kuis', 'kehadiran']);

            
            $table->string('gaya_belajar')->nullable()->after('tingkat_kesulitan');
        });
    }
};
