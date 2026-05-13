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
        Schema::create('kwitansi_iuran_wargas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('iuran_id')->constrained('iuran_wargas')->cascadeOnDelete();
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
        Schema::dropIfExists('kwitansi_iuran_wargas');
    }
};
