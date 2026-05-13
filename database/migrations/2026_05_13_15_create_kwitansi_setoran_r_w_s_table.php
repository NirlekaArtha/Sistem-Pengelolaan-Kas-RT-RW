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
        Schema::create('kwitansi_setoran_r_w_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_setoran')->constrained('setoran_r_w_s')->cascadeOnDelete();
            $table->string('nomor_kwitansi')->unique();
            $table->string('file_path')->nullable();
            $table->date('tanggal_cetak');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kwitansi_setoran_r_w_s');
    }
};
