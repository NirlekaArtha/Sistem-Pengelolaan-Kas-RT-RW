<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RT extends Model
{
    /** @use HasFactory<\Database\Factories\RTFactory> */
    use HasFactory;

    protected $table = 'r_t_s';

    protected $fillable = [
        'id_rw',
        'id_user',
        'nomor_rt',
        'nama',
        'alamat',
        'no_telepon',
    ];

    // ─── Belongs To ──────────────────────────────────────────────────────────

    public function rw(): BelongsTo
    {
        return $this->belongsTo(RW::class, 'id_rw');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // ─── Has Many ────────────────────────────────────────────────────────────

    public function wargas(): HasMany
    {
        return $this->hasMany(Warga::class, 'id_rt');
    }

    public function kasMasukRTs(): HasMany
    {
        return $this->hasMany(KasMasukRT::class, 'id_rt');
    }

    public function kasKeluarRTs(): HasMany
    {
        return $this->hasMany(KasKeluarRT::class, 'id_rt');
    }

    public function kasBulananRTs(): HasMany
    {
        return $this->hasMany(KasBulananRT::class, 'id_rt');
    }

    public function setoranRWs(): HasMany
    {
        return $this->hasMany(SetoranRW::class, 'id_rt');
    }

    public function jenisIuranWargas(): HasMany
    {
        return $this->hasMany(JenisIuranWarga::class, 'id_rt');
    }

    public function iuranWargas(): HasMany
    {
        return $this->hasMany(IuranWarga::class, 'id_rt');
    }
}
