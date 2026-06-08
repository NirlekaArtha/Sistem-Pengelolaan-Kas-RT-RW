<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RW extends Model
{
    /** @use HasFactory<\Database\Factories\RWFactory> */
    use HasFactory;

    protected $table = 'r_w_s';

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
        return $this->hasMany(KasRW::class, 'id_rw')->where('tipe', 'masuk');
    }

    public function kasKeluarRWs(): HasMany
    {
        return $this->hasMany(KasRW::class, 'id_rw')->where('tipe', 'keluar');
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
