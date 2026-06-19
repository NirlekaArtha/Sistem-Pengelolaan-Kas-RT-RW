<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
        'profile_picture',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'auth' => true, // semua user boleh lihat halaman login
            'rw' => $this->role === UserRole::RW,
            'rt' => $this->role === UserRole::RT,
            'warga' => $this->role === UserRole::WARGA,
            default => false,
        };
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

    public function profileRecord(): RW|RT|Warga|null
    {
        return match ($this->role) {
            UserRole::RW => $this->rw,
            UserRole::RT => $this->rt,
            UserRole::WARGA => $this->warga,
            default => null,
        };
    }

    protected static function booted(): void
    {
        static::updating(function (User $user): void {
            if (! $user->isDirty('profile_picture')) {
                return;
            }

            $originalProfilePicture = $user->getOriginal('profile_picture');

            if (filled($originalProfilePicture)) {
                Storage::disk('public')->delete($originalProfilePicture);
            }
        });
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->profile_picture
            ? Storage::url($this->profile_picture)
            : null;
    }
}
