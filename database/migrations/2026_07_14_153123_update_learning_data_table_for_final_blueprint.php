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
            // 1. Tambah kolom baru sesuai blueprint
            // Tipe data program_id adalah bigint unsigned untuk mencocokkan id pada tabel programs
            $table->unsignedBigInteger('program_id')->nullable()->after('user_id');
            $table->unsignedTinyInteger('nilai_tugas')->nullable()->after('nilai');
            $table->unsignedTinyInteger('nilai_kuis')->nullable()->after('nilai_tugas');
            $table->enum('kehadiran', ['Kurang', 'Cukup', 'Baik'])->default('Baik')->after('nilai_kuis');

            // 2. Hapus kolom gaya_belajar yang tidak digunakan lagi
            $table->dropColumn('gaya_belajar');

            // 3. Definisikan foreign key untuk program_id
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learning_data', function (Blueprint $table) {
            // 1. Hapus foreign key constraint dan kolom program_id
            $table->dropForeign(['program_id']);
            $table->dropColumn('program_id');

            // 2. Hapus kolom baru lainnya
            $table->dropColumn(['nilai_tugas', 'nilai_kuis', 'kehadiran']);

            // 3. Kembalikan kolom gaya_belajar
            $table->string('gaya_belajar')->nullable()->after('tingkat_kesulitan');
        });
    }
};
