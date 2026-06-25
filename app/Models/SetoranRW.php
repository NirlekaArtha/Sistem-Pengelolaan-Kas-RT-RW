<?php

namespace App\Models;

use App\Enums\SetoranStatusValidasi;
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

    protected $table = 'setoran_r_w_s';

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
            get: fn (?string $value): ?string => self::normalizePeriode($value),
            set: fn (?string $value): ?string => self::normalizePeriode($value),
        );
    }

    // ─── Has One ─────────────────────────────────────────────────────────────

    public function kwitansi(): HasOne
    {
        return $this->hasOne(KwitansiSetoranRW::class, 'id_setoran');
    }

    private static function normalizePeriode(?string $value): ?string
    {
        if (blank($value)) {
            return $value;
        }

        if (preg_match('/^\d{4}-\d{2}$/', $value) === 1) {
            return $value;
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}/', $value) === 1) {
            return substr($value, 0, 7);
        }

        return $value;
    }
}
