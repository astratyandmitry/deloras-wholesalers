<?php

namespace App\Models\Enums;

use Filament\Support\Contracts\HasLabel;

enum OrderQuantityType: string implements HasLabel
{
    case PIECE = 'piece';
    case SERIES = 'series';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::PIECE => 'Штука',
            self::SERIES => 'Серия',
        };
    }

    public function getShortLabel(): string
    {
        return match ($this) {
            self::PIECE => 'шт.',
            self::SERIES => 'сер.',
        };
    }
}
