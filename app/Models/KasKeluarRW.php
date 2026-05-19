<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KasKeluarRW extends Model
{
    /** @use HasFactory<\Database\Factories\KasKeluarRWFactory> */
    use HasFactory;

    protected $table = "kas_keluar_r_w_s";

    protected $fillable = [
        "id_rw",
        "jenis",
        "jumlah",
        "penerima",
        "keterangan",
        "tanggal",
    ];

    protected $casts = [
        "jumlah" => "decimal:2",
        "tanggal" => "date",
    ];

    // ─── Belongs To ──────────────────────────────────────────────────────────

    public function rw(): BelongsTo
    {
        return $this->belongsTo(RW::class, "id_rw");
    }
}
