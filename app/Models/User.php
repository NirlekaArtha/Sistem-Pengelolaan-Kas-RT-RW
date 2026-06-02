<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = ["name", "email", "role", "password"];

    protected $hidden = ["password", "remember_token"];

    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            "auth" => true, // semua user boleh lihat halaman login
            "rw" => $this->role === "RW",
            "rt" => $this->role === "RT",
            "warga" => $this->role === "Warga",
            default => false,
        };
    }
    // ─── Relasi 1:1 sesuai role ───────────────────────────────────────────────

    public function warga(): HasOne
    {
        return $this->hasOne(Warga::class, "id_user");
    }

    public function rt(): HasOne
    {
        return $this->hasOne(RT::class, "id_user");
    }

    public function rw(): HasOne
    {
        return $this->hasOne(RW::class, "id_user");
    }
}
