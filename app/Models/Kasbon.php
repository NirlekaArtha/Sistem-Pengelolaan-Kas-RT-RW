<?php

namespace App\Models;

use Database\Factories\KasbonFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kasbon extends Model
{
    /** @use HasFactory<KasbonFactory> */
    use HasFactory;

    protected $fillable = [
        'id_petugas',
        'jumlah',
        'tanggal',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal' => 'date',
    ];

    // ─── Belongs To ──────────────────────────────────────────────────────────

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }
}
