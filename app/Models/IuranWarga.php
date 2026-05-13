<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class IuranWarga extends Model
{
    /** @use HasFactory<\Database\Factories\IuranWargaFactory> */
    use HasFactory;

    protected $fillable = [
        'id_warga',
        'id_jenis_iuran',
        'id_rt',
        'periode',
        'tanggal_bayar',
        'status',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
    ];

    // ─── Belongs To ──────────────────────────────────────────────────────────

    public function warga(): BelongsTo
    {
        return $this->belongsTo(Warga::class, 'id_warga');
    }

    public function jenisIuran(): BelongsTo
    {
        return $this->belongsTo(JenisIuranWarga::class, 'id_jenis_iuran');
    }

    public function rt(): BelongsTo
    {
        return $this->belongsTo(RT::class, 'id_rt');
    }

    // ─── Has One ─────────────────────────────────────────────────────────────

    public function kwitansi(): HasOne
    {
        return $this->hasOne(KwitansiIuranWarga::class, 'iuran_id');
    }
}
