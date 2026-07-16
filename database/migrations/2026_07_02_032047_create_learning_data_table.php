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
        Schema::dropIfExists('learning_data');
        Schema::create('learning_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('mata_pelajaran', 50);
            $table->decimal('nilai', 5, 2)->nullable();
            $table->enum('tingkat_kesulitan', ['Mudah', 'Sedang', 'Sulit'])->default('Sedang');
            $table->string('gaya_belajar')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_data');
    }
};
