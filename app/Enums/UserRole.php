<?php

namespace App\Enums;

use App\Enums\Concerns\HasEnumOptions;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum UserRole: string implements HasColor, HasIcon, HasLabel
{
    use HasEnumOptions;

    case WARGA = 'Warga';
    case RT = 'RT';
    case RW = 'RW';

    public function getLabel(): ?string
    {
        return $this->value;
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::WARGA => 'info',
            self::RT => 'warning',
            self::RW => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::WARGA => 'heroicon-o-home-modern',
            self::RT => 'heroicon-o-building-office-2',
            self::RW => 'heroicon-o-shield-check',
        };
    }

    public function getPanelId(): string
    {
        return match ($this) {
            self::WARGA => 'warga',
            self::RT => 'rt',
            self::RW => 'rw',
        };
    }

    public function getPath(): string
    {
        return '/'.$this->getPanelId();
    }
}
