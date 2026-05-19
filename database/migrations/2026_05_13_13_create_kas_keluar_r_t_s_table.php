<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("kas_keluar_r_t_s", function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_rt")->constrained("r_t_s")->cascadeOnDelete();
            $table->enum("jenis", ["operasional", "kegiatan", "lainnya"]);
            $table->decimal("jumlah", 15, 2);
            $table->text("penerima");
            $table->text("keterangan")->nullable();
            $table->date("tanggal");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("kas_keluar_r_t_s");
    }
};
