<?php

namespace App\Support;

class ExcelHelper
{
    public static function normalizeHeader(string $header): string
    {
        return strtolower(
            str_replace([' ', '_', '.', '(', ')', '-'], '', trim($header))
        );
    }

    public static function normalizeRow(array $row): array
    {
        $result = [];

        foreach ($row as $key => $value) {
            $result[self::normalizeHeader($key)] = $value;
        }

        return $result;
    }
}
