<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'=> 'hashed',
        ];
    }

    // ─── Relasi 1:1 sesuai role ───────────────────────────────────────────────

    public function warga(): HasOne
    {
        return $this->hasOne(Warga::class, 'id_user');
    }

    public function rt(): HasOne
    {
        return $this->hasOne(RT::class, 'id_user');
    }

    public function rw(): HasOne
    {
        return $this->hasOne(RW::class, 'id_user');
    }
}
