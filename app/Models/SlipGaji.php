<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlipGaji extends Model
{
    /** @use HasFactory<\Database\Factories\SlipGajiFactory> */
    use HasFactory;

    protected $fillable = [
        'id_petugas',
        'total',
        'tanggal',
        'file_path',
    ];

    protected $casts = [
        'total'   => 'decimal:2',
        'tanggal' => 'date',
    ];

    // ─── Belongs To ──────────────────────────────────────────────────────────

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }
}
