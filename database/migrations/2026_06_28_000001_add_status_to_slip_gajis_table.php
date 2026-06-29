<?php

use App\Enums\SlipGajiStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('slip_gajis', function (Blueprint $table) {
            $table
                ->string('status')
                ->default(SlipGajiStatus::BELUM_DIBAYAR->value)
                ->after('tanggal');
        });
    }

    public function down(): void
    {
        Schema::table('slip_gajis', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
