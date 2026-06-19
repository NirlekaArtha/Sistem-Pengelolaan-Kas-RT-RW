<?php

namespace App\Models;

use Database\Factories\KwitansiSetoranRWFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KwitansiSetoranRW extends Model
{
    /** @use HasFactory<KwitansiSetoranRWFactory> */
    use HasFactory;

    protected $table = 'kwitansi_setoran_r_w_s';

    protected $fillable = [
        'id_setoran',
        'nomor_kwitansi',
        'file_path',
        'tanggal_cetak',
    ];

    protected $casts = [
        'tanggal_cetak' => 'date',
    ];

    // ─── Belongs To ──────────────────────────────────────────────────────────

    public function setoran(): BelongsTo
    {
        return $this->belongsTo(SetoranRW::class, 'id_setoran');
    }
}
