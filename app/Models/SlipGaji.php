<?php

namespace App\Models;

use App\Enums\SlipGajiStatus;
use App\Support\Periode;
use Database\Factories\SlipGajiFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'periode',
        'status',
        'file_path',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'status' => SlipGajiStatus::class,
    ];

    // ─── Belongs To ──────────────────────────────────────────────────────────

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }

    protected function periode(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value): ?string => Periode::normalize($value),
            set: fn (?string $value): ?string => Periode::normalize($value),
        );
    }

    protected function periodeLabel(): Attribute
    {
        return Attribute::make(
            get: fn (): string => Periode::label($this->periode),
        );
    }
}
