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
        Schema::create('jenis_iuran_wargas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rt')->constrained('r_t_s')->cascadeOnDelete();
            $table->string('jenis_iuran');
            $table->decimal('jumlah', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_iuran_wargas');
    }
};
