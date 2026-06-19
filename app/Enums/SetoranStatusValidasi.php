<?php

namespace App\Enums;

use App\Enums\Concerns\HasEnumOptions;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum SetoranStatusValidasi: string implements HasColor, HasIcon, HasLabel
{
    use HasEnumOptions;

    case PENDING = 'pending';
    case VALID = 'valid';
    case DITOLAK = 'ditolak';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::VALID => 'Valid',
            self::DITOLAK => 'Ditolak',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::VALID => 'success',
            self::DITOLAK => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::PENDING => 'heroicon-o-clock',
            self::VALID => 'heroicon-o-check-circle',
            self::DITOLAK => 'heroicon-o-x-circle',
        };
    }
}
