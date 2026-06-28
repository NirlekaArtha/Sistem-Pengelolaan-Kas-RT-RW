<?php

namespace App\Models;

use App\Enums\KasTipe;
use Database\Factories\RWFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RW extends Model
{
    /** @use HasFactory<RWFactory> */
    use HasFactory;

    protected $table = 'rw';

    protected $fillable = [
        'id_user',
        'nomor_rw',
        'nama',
        'alamat',
        'no_telepon',
    ];

    // ─── Belongs To ──────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // ─── Has Many ────────────────────────────────────────────────────────────

    public function rts(): HasMany
    {
        return $this->hasMany(RT::class, 'id_rw');
    }

    public function petugas(): HasMany
    {
        return $this->hasMany(Petugas::class, 'id_rw');
    }

    public function kasRWs(): HasMany
    {
        return $this->hasMany(KasRW::class, 'id_rw');
    }

    public function kasMasukRWs(): HasMany
    {
        return $this->hasMany(KasRW::class, 'id_rw')->where('tipe', KasTipe::MASUK->value);
    }

    public function kasKeluarRWs(): HasMany
    {
        return $this->hasMany(KasRW::class, 'id_rw')->where('tipe', KasTipe::KELUAR->value);
    }

    public function kasBulananRWs(): HasMany
    {
        return $this->hasMany(KasBulananRW::class, 'id_rw');
    }

    public function setoranRWs(): HasMany
    {
        return $this->hasMany(SetoranRW::class, 'id_rw')
            ->orderByDesc('periode')
            ->orderByDesc('tanggal_setor');
    }
}
