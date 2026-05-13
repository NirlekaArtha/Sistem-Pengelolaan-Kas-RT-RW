<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SetoranRW extends Model
{
    /** @use HasFactory<\Database\Factories\SetoranRWFactory> */
    use HasFactory;

    protected $table = 'setoran_r_w_s';

    protected $fillable = [
        'id_rt',
        'id_rw',
        'periode',
        'tanggal_setor',
        'jumlah_setor',
        'status_validasi',
    ];

    protected $casts = [
        'tanggal_setor' => 'date',
        'jumlah_setor'  => 'decimal:2',
    ];

    // ─── Belongs To ──────────────────────────────────────────────────────────

    public function rt(): BelongsTo
    {
        return $this->belongsTo(RT::class, 'id_rt');
    }

    public function rw(): BelongsTo
    {
        return $this->belongsTo(RW::class, 'id_rw');
    }

    // ─── Has One ─────────────────────────────────────────────────────────────

    public function kwitansi(): HasOne
    {
        return $this->hasOne(KwitansiSetoranRW::class, 'id_setoran');
    }
}
