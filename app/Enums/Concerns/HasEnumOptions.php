<?php

namespace App\Enums\Concerns;

use BackedEnum;
use Filament\Support\Contracts\HasLabel;

trait HasEnumOptions
{
    public static function values(): array
    {
        return array_map(
            fn (BackedEnum $case): string|int => $case->value,
            static::cases(),
        );
    }

    public static function options(): array
    {
        return array_reduce(
            static::cases(),
            function (array $carry, BackedEnum $case): array {
                $carry[$case->value] = $case instanceof HasLabel
                    ? $case->getLabel() ?? $case->name
                    : $case->name;

                return $carry;
            },
            [],
        );
    }
}
