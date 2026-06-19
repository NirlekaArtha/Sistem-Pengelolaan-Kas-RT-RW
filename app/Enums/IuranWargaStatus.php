<?php

namespace App\Enums;

use App\Enums\Concerns\HasEnumOptions;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum IuranWargaStatus: string implements HasColor, HasIcon, HasLabel
{
    use HasEnumOptions;

    case BELUM_BAYAR = 'belum bayar';
    case DIBAYAR = 'dibayar';
    case TELAT = 'telat';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::BELUM_BAYAR => 'Belum Bayar',
            self::DIBAYAR => 'Dibayar',
            self::TELAT => 'Telat',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::BELUM_BAYAR => 'warning',
            self::DIBAYAR => 'success',
            self::TELAT => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::BELUM_BAYAR => 'heroicon-m-clock',
            self::DIBAYAR => 'heroicon-m-check-circle',
            self::TELAT => 'heroicon-m-x-circle',
        };
    }
}
