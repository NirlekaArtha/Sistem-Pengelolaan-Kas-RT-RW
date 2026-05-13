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
        Schema::create('setoran_r_w_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rt')->constrained('r_t_s')->cascadeOnDelete();
            $table->foreignId('id_rw')->constrained('r_w_s')->cascadeOnDelete();
            $table->string('periode'); // format: YYYY-MM
            $table->date('tanggal_setor');
            $table->decimal('jumlah_setor', 15, 2);
            $table->enum('status_validasi', ['pending', 'valid', 'ditolak'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setoran_r_w_s');
    }
};
