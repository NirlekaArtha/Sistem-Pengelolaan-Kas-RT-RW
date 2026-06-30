<?php

namespace App\Models;

use App\Support\Periode;
use Database\Factories\KasBulananRTFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KasBulananRT extends Model
{
    /** @use HasFactory<KasBulananRTFactory> */
    use HasFactory;

    protected $table = 'kas_bulanan_rt';

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
        'total_pendapatan' => 'decimal:2',
        'total_pengeluaran' => 'decimal:2',
        'saldo_awal' => 'decimal:2',
        'saldo_akhir' => 'decimal:2',
        'total_pendapatan_bersih' => 'decimal:2',
    ];

    // ─── Belongs To ──────────────────────────────────────────────────────────

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
}
