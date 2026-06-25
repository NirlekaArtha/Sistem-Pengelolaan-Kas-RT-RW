<?php

namespace App\Enums;

use App\Enums\Concerns\HasEnumOptions;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PetugasTugas: string implements HasColor, HasIcon, HasLabel
{
    use HasEnumOptions;

    case SATPAM = 'satpam';
    case KEBERSIHAN = 'kebersihan';
    case SAMPAH = 'sampah';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::SATPAM => 'Petugas Keamanan/Security',
            self::KEBERSIHAN => 'Petugas Kebersihan',
            self::SAMPAH => 'Petugas Pengangkutan Sampah',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::SATPAM => 'success',
            self::KEBERSIHAN => 'info',
            self::SAMPAH => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::SATPAM => 'heroicon-o-shield-check',
            self::KEBERSIHAN => 'heroicon-o-sparkles',
            self::SAMPAH => 'heroicon-o-trash',
        };
    }
}
