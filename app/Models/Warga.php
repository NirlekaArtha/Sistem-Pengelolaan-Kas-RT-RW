<?php

namespace App\Models;

use Database\Factories\WargaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warga extends Model
{
    /** @use HasFactory<WargaFactory> */
    use HasFactory;

    protected $fillable = [
        'id_rt',
        'id_user',
        'nama_kepala_keluarga',
        'alamat',
        'no_telepon',
    ];

    // ─── Belongs To ──────────────────────────────────────────────────────────

    public function rt(): BelongsTo
    {
        return $this->belongsTo(RT::class, 'id_rt');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // ─── Has Many ────────────────────────────────────────────────────────────

    public function iuranWargas(): HasMany
    {
        return $this->hasMany(IuranWarga::class, 'id_warga');
    }
}
