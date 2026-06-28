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
        Schema::create('kas_bulanan_rt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rt')->constrained('rt')->cascadeOnDelete();
            $table->string('periode'); // format: YYYY-MM
            $table->decimal('total_pendapatan', 15, 2);
            $table->decimal('total_pengeluaran', 15, 2);
            $table->decimal('saldo_awal', 15, 2);
            $table->decimal('saldo_akhir', 15, 2);
            $table->decimal('total_pendapatan_bersih', 15, 2);
            $table->string('file_path')->nullable();
            $table->timestamps();

            $table->unique(['id_rt', 'periode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas_bulanan_rt');
    }
};
