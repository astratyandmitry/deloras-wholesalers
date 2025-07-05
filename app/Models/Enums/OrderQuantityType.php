<?php

namespace App\Models\Enums;

enum OrderQuantityType: string
{
    case PIECE = 'piece';
    case PACK = 'pack';
    case SERIES = 'series';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
