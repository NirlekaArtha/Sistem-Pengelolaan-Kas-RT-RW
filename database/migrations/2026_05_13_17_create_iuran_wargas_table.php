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
        Schema::create('iuran_wargas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_warga')->constrained('wargas')->cascadeOnDelete();
            $table->foreignId('id_jenis_iuran')->constrained('jenis_iuran_wargas')->cascadeOnDelete();
            $table->foreignId('id_rt')->constrained('rt')->cascadeOnDelete();
            $table->string('periode'); // format: YYYY-MM
            $table->date('tanggal_bayar')->nullable();
            $table->enum('status', ['belum bayar', 'dibayar', 'telat'])->default('belum bayar');
            $table->timestamps();

            $table->unique(['id_warga', 'id_jenis_iuran', 'periode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iuran_wargas');
    }
};
