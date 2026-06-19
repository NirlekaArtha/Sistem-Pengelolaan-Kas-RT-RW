<?php

namespace App\Models;

use App\Enums\KasTipe;
use Database\Factories\RTFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RT extends Model
{
    /** @use HasFactory<RTFactory> */
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

    public function kasRTs(): HasMany
    {
        return $this->hasMany(KasRT::class, 'id_rt');
    }

    public function kasMasukRTs(): HasMany
    {
        return $this->hasMany(KasRT::class, 'id_rt')->where('tipe', KasTipe::MASUK->value);
    }

    public function kasKeluarRTs(): HasMany
    {
        return $this->hasMany(KasRT::class, 'id_rt')->where('tipe', KasTipe::KELUAR->value);
    }

    public function kasBulananRTs(): HasMany
    {
        return $this->hasMany(KasBulananRT::class, 'id_rt');
    }

    public function setoranRWs(): HasMany
    {
        return $this->hasMany(SetoranRW::class, 'id_rt')
            ->orderByDesc('periode')
            ->orderByDesc('tanggal_setor');
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
