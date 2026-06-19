<?php

namespace App\Enums;

use App\Enums\Concerns\HasEnumOptions;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum KasTipe: string implements HasColor, HasIcon, HasLabel
{
    use HasEnumOptions;

    case MASUK = 'masuk';
    case KELUAR = 'keluar';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::MASUK => 'Masuk',
            self::KELUAR => 'Keluar',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::MASUK => 'success',
            self::KELUAR => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::MASUK => 'heroicon-o-arrow-trending-up',
            self::KELUAR => 'heroicon-o-arrow-trending-down',
        };
    }
}
