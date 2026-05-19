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
        Schema::create('kas_r_w_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rw')->constrained('r_w_s')->cascadeOnDelete();
            $table->enum('tipe', ['masuk', 'keluar']);
            $table->enum('jenis', [
                'donasi', 'sponsorship', 'hibah', 'hasil usaha',
                'operasional', 'kegiatan',
                'lainnya'
            ]);
            $table->decimal('jumlah', 15, 2);
            $table->string('sumber_tujuan');
            $table->text('keterangan')->nullable();
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas_r_w_s');
    }
};
