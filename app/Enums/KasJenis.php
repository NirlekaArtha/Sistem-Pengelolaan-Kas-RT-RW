<?php

namespace App\Enums;

use App\Enums\Concerns\HasEnumOptions;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum KasJenis: string implements HasColor, HasIcon, HasLabel
{
    use HasEnumOptions;

    case DONASI = 'donasi';
    case SPONSORSHIP = 'sponsorship';
    case HIBAH = 'hibah';
    case HASIL_USAHA = 'hasil usaha';
    case OPERASIONAL = 'operasional';
    case KEGIATAN = 'kegiatan';
    case LAINNYA = 'lainnya';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DONASI => 'Donasi',
            self::SPONSORSHIP => 'Sponsorship',
            self::HIBAH => 'Hibah',
            self::HASIL_USAHA => 'Hasil Usaha',
            self::OPERASIONAL => 'Operasional',
            self::KEGIATAN => 'Kegiatan',
            self::LAINNYA => 'Lainnya',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DONASI => 'success',
            self::SPONSORSHIP => 'warning',
            self::HIBAH => 'info',
            self::HASIL_USAHA => 'primary',
            self::OPERASIONAL => 'danger',
            self::KEGIATAN => 'gray',
            self::LAINNYA => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::DONASI => 'heroicon-o-heart',
            self::SPONSORSHIP => 'heroicon-o-megaphone',
            self::HIBAH => 'heroicon-o-gift',
            self::HASIL_USAHA => 'heroicon-o-briefcase',
            self::OPERASIONAL => 'heroicon-o-cog-6-tooth',
            self::KEGIATAN => 'heroicon-o-calendar-days',
            self::LAINNYA => 'heroicon-o-ellipsis-horizontal-circle',
        };
    }

    public function supportsTipe(KasTipe|string $tipe): bool
    {
        $tipe = $tipe instanceof KasTipe ? $tipe : KasTipe::from($tipe);

        return match ($this) {
            self::DONASI,
            self::SPONSORSHIP,
            self::HIBAH,
            self::HASIL_USAHA => $tipe === KasTipe::MASUK,
            self::OPERASIONAL,
            self::KEGIATAN => $tipe === KasTipe::KELUAR,
            self::LAINNYA => true,
        };
    }

    public static function optionsForTipe(KasTipe|string|null $tipe): array
    {
        if (blank($tipe)) {
            return self::options();
        }

        $tipe = $tipe instanceof KasTipe ? $tipe : KasTipe::from($tipe);

        return array_reduce(self::cases(), function (array $carry, self $case) use ($tipe): array {
            if ($case->supportsTipe($tipe)) {
                $carry[$case->value] = $case->getLabel();
            }

            return $carry;
        }, []);
    }

    public static function valuesForTipe(KasTipe|string|null $tipe): array
    {
        return array_keys(self::optionsForTipe($tipe));
    }
}
