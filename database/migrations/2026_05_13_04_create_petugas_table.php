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
        Schema::create('petugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rw')->constrained('rw')->cascadeOnDelete();
            $table->enum('tugas', ['satpam', 'kebersihan', 'sampah']);
            $table->string('nama');
            $table->string('alamat');
            $table->decimal('gaji_pokok', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petugas');
    }
};
