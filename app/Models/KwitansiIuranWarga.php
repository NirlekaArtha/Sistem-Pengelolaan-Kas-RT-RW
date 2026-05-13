<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KwitansiIuranWarga extends Model
{
    /** @use HasFactory<\Database\Factories\KwitansiIuranWargaFactory> */
    use HasFactory;

    protected $fillable = [
        'iuran_id',
        'nomor_kwitansi',
        'file_path',
        'tanggal_cetak',
    ];

    protected $casts = [
        'tanggal_cetak' => 'date',
    ];

    // ─── Belongs To ──────────────────────────────────────────────────────────

    public function iuranWarga(): BelongsTo
    {
        return $this->belongsTo(IuranWarga::class, 'iuran_id');
    }
}
