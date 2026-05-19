<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KasRT extends Model
{
    use HasFactory;

    protected $table = 'kas_r_t_s';

    protected $fillable = [
        'id_rt',
        'tipe',
        'jenis',
        'jumlah',
        'sumber_tujuan',
        'keterangan',
        'tanggal',
    ];

    protected $casts = [
        'jumlah'  => 'decimal:2',
        'tanggal' => 'date',
    ];

    // ─── Belongs To ──────────────────────────────────────────────────────────

    public function rt(): BelongsTo
    {
        return $this->belongsTo(RT::class, 'id_rt');
    }
}
