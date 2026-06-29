<?php

namespace App\Enums;

use App\Enums\Concerns\HasEnumOptions;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum SlipGajiStatus: string implements HasColor, HasIcon, HasLabel
{
    use HasEnumOptions;

    case BELUM_DIBAYAR = 'belum dibayar';
    case TELAH_DIBAYAR = 'telah dibayar';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::BELUM_DIBAYAR => 'Belum Dibayar',
            self::TELAH_DIBAYAR => 'Telah Dibayar',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::BELUM_DIBAYAR => 'warning',
            self::TELAH_DIBAYAR => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::BELUM_DIBAYAR => 'heroicon-m-clock',
            self::TELAH_DIBAYAR => 'heroicon-m-check-circle',
        };
    }
}
