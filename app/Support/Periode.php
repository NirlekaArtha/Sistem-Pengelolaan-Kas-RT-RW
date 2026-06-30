<?php

namespace App\Support;

use Carbon\Carbon;

class Periode
{
    public static function normalize(?string $value): ?string
    {
        if (blank($value)) {
            return $value;
        }

        if (preg_match('/^\d{4}-\d{2}$/', $value) === 1) {
            return $value;
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}/', $value) === 1) {
            return substr($value, 0, 7);
        }

        return $value;
    }

    public static function label(?string $value, string $fallback = '-'): string
    {
        $normalized = self::normalize($value);

        if (blank($normalized)) {
            return $fallback;
        }

        try {
            return Carbon::createFromFormat('Y-m', $normalized)->translatedFormat('F Y');
        } catch (\Throwable) {
            return $normalized;
        }
    }

    public static function labelFromDate(?string $value, string $fallback = '-'): string
    {
        if (blank($value)) {
            return $fallback;
        }

        try {
            return Carbon::parse($value)->translatedFormat('F Y');
        } catch (\Throwable) {
            return $value;
        }
    }

    /**
     * @param  iterable<int|string, string>  $values
     * @return array<string, string>
     */
    public static function selectOptions(iterable $values): array
    {
        $options = [];

        foreach ($values as $value) {
            $normalized = self::normalize((string) $value);

            if (blank($normalized)) {
                continue;
            }

            $options[$normalized] = self::label($normalized);
        }

        return $options;
    }
}
