<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KasBulananRT extends Model
{
    /** @use HasFactory<\Database\Factories\KasBulananRTFactory> */
    use HasFactory;

    protected $table = 'kas_bulanan_r_t_s';

    protected $fillable = [
        'id_rt',
        'periode',
        'total_pendapatan',
        'total_pengeluaran',
        'saldo_awal',
        'saldo_akhir',
        'total_pendapatan_bersih',
        'file_path',
    ];

    protected $casts = [
        'total_pendapatan'        => 'decimal:2',
        'total_pengeluaran'       => 'decimal:2',
        'saldo_awal'              => 'decimal:2',
        'saldo_akhir'             => 'decimal:2',
        'total_pendapatan_bersih' => 'decimal:2',
    ];

    // ─── Belongs To ──────────────────────────────────────────────────────────

    public function rt(): BelongsTo
    {
        return $this->belongsTo(RT::class, 'id_rt');
    }
}
