<?php

namespace App\Models;

use App\Enums\SlipGajiStatus;
use Database\Factories\SlipGajiFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlipGaji extends Model
{
    /** @use HasFactory<SlipGajiFactory> */
    use HasFactory;

    protected $fillable = [
        'id_petugas',
        'total',
        'tanggal',
        'status',
        'file_path',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'tanggal' => 'date',
        'status' => SlipGajiStatus::class,
    ];

    // ─── Belongs To ──────────────────────────────────────────────────────────

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }
}
