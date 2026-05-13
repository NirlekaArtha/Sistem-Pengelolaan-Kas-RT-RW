<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Petugas extends Model
{
    /** @use HasFactory<\Database\Factories\PetugasFactory> */
    use HasFactory;

    protected $fillable = [
        'id_rw',
        'tugas',
        'nama',
        'alamat',
        'gaji_pokok',
    ];

    protected $casts = [
        'gaji_pokok' => 'decimal:2',
    ];

    // ─── Belongs To ──────────────────────────────────────────────────────────

    public function rw(): BelongsTo
    {
        return $this->belongsTo(RW::class, 'id_rw');
    }

    // ─── Has Many ────────────────────────────────────────────────────────────

    public function kasbons(): HasMany
    {
        return $this->hasMany(Kasbon::class, 'id_petugas');
    }

    public function slipGajis(): HasMany
    {
        return $this->hasMany(SlipGaji::class, 'id_petugas');
    }
}
