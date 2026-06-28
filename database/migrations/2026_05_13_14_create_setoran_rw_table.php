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
        Schema::create('setoran_rw', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rt')->constrained('rt')->cascadeOnDelete();
            $table->foreignId('id_rw')->constrained('rw')->cascadeOnDelete();
            $table->string('periode'); // format: YYYY-MM
            $table->date('tanggal_setor');
            $table->decimal('jumlah_setor', 15, 2);
            $table->enum('status_validasi', ['pending', 'valid', 'ditolak'])->default('pending');
            $table->timestamps();

            $table->unique(['id_rt', 'periode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setoran_rw');
    }
};
