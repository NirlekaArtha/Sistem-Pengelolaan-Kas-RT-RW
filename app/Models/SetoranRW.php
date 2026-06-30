<?php

namespace App\Models;

use App\Enums\SetoranStatusValidasi;
use App\Support\Periode;
use Database\Factories\SetoranRWFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SetoranRW extends Model
{
    /** @use HasFactory<SetoranRWFactory> */
    use HasFactory;

    protected $table = 'setoran_rw';

    protected $fillable = [
        'id_rt',
        'id_rw',
        'periode',
        'tanggal_setor',
        'jumlah_setor',
        'status_validasi',
    ];

    protected $casts = [
        'tanggal_setor' => 'date',
        'jumlah_setor' => 'decimal:2',
        'status_validasi' => SetoranStatusValidasi::class,
    ];

    // ─── Belongs To ──────────────────────────────────────────────────────────

    public function rt(): BelongsTo
    {
        return $this->belongsTo(RT::class, 'id_rt');
    }

    public function rw(): BelongsTo
    {
        return $this->belongsTo(RW::class, 'id_rw');
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

    // ─── Has One ─────────────────────────────────────────────────────────────

    public function kwitansi(): HasOne
    {
        return $this->hasOne(KwitansiSetoranRW::class, 'id_setoran');
    }

}
