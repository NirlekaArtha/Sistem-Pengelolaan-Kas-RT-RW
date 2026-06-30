<?php

namespace App\Models;

use App\Enums\IuranWargaStatus;
use App\Support\Periode;
use Database\Factories\IuranWargaFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class IuranWarga extends Model
{
    /** @use HasFactory<IuranWargaFactory> */
    use HasFactory;

    protected $fillable = [
        'id_warga',
        'id_jenis_iuran',
        'id_rt',
        'periode',
        'tanggal_bayar',
        'status',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'status' => IuranWargaStatus::class,
    ];

    // ─── Belongs To ──────────────────────────────────────────────────────────

    public function warga(): BelongsTo
    {
        return $this->belongsTo(Warga::class, 'id_warga');
    }

    public function jenisIuran(): BelongsTo
    {
        return $this->belongsTo(JenisIuranWarga::class, 'id_jenis_iuran');
    }

    public function rt(): BelongsTo
    {
        return $this->belongsTo(RT::class, 'id_rt');
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
        return $this->hasOne(KwitansiIuranWarga::class, 'iuran_id');
    }
}
