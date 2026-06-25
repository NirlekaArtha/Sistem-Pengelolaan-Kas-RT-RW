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
        Schema::table('iuran_wargas', function (Blueprint $table) {
            $table->unique(
                ['id_warga', 'id_jenis_iuran', 'periode'],
                'iuran_wargas_warga_jenis_periode_unique',
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iuran_wargas', function (Blueprint $table) {
            $table->dropUnique('iuran_wargas_warga_jenis_periode_unique');
        });
    }
};
