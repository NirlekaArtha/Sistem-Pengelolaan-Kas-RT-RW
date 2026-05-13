<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisIuranWarga extends Model
{
    /** @use HasFactory<\Database\Factories\JenisIuranWargaFactory> */
    use HasFactory;

    protected $fillable = [
        'id_rt',
        'jenis_iuran',
        'jumlah',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
    ];

    // ─── Belongs To ──────────────────────────────────────────────────────────

    public function rt(): BelongsTo
    {
        return $this->belongsTo(RT::class, 'id_rt');
    }

    // ─── Has Many ────────────────────────────────────────────────────────────

    public function iuranWargas(): HasMany
    {
        return $this->hasMany(IuranWarga::class, 'id_jenis_iuran');
    }
}
