<?php

namespace App\Support;

class StringHelper
{
    public static function normalizeNumber(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        return preg_replace('/[^0-9]/', '', trim($value));
    }
}
