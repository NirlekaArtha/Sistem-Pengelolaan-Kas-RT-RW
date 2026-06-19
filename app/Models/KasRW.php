<?php

namespace App\Models;

use App\Enums\KasJenis;
use App\Enums\KasTipe;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KasRW extends Model
{
    use HasFactory;

    protected $table = 'kas_r_w_s';

    protected $fillable = [
        'id_rw',
        'tipe',
        'jenis',
        'jumlah',
        'sumber_tujuan',
        'keterangan',
        'tanggal',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal' => 'date',
        'tipe' => KasTipe::class,
        'jenis' => KasJenis::class,
    ];

    // ─── Belongs To ──────────────────────────────────────────────────────────

    public function rw(): BelongsTo
    {
        return $this->belongsTo(RW::class, 'id_rw');
    }
}
