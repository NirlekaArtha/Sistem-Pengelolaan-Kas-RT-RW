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
        Schema::create('rt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rw')->constrained('rw')->cascadeOnDelete();
            $table->foreignId('id_user')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('nomor_rt');
            $table->string('nama');
            $table->string('alamat');
            $table->string('no_telepon');
            $table->timestamps();

            $table->unique(['id_rw', 'nomor_rt']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rt');
    }
};
