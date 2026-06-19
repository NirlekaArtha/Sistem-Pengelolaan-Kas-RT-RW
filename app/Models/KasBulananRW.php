<?php

namespace App\Models;

use Database\Factories\KasBulananRWFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KasBulananRW extends Model
{
    /** @use HasFactory<KasBulananRWFactory> */
    use HasFactory;

    protected $table = 'kas_bulanan_r_w_s';

    protected $fillable = [
        'id_rw',
        'periode',
        'total_pendapatan',
        'total_pengeluaran',
        'total_pendapatan_bersih',
        'saldo_awal',
        'saldo_akhir',
        'file_path',
    ];

    protected $casts = [
        'total_pendapatan' => 'decimal:2',
        'total_pengeluaran' => 'decimal:2',
        'total_pendapatan_bersih' => 'decimal:2',
        'saldo_awal' => 'decimal:2',
        'saldo_akhir' => 'decimal:2',
    ];

    // ─── Belongs To ──────────────────────────────────────────────────────────

    public function rw(): BelongsTo
    {
        return $this->belongsTo(RW::class, 'id_rw');
    }
}
