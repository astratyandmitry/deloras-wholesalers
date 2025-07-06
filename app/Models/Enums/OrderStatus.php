<?php

namespace App\Models\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasLabel, HasColor
{
    case PENDING = 'pending';
    case AGREED = 'agreed';
    case UPDATED = 'updated';
    case CANCELED = 'canceled';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'Новый',
            self::AGREED => 'Подтвержден',
            self::UPDATED => 'Изменен',
            self::CANCELED => 'Отменен',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            OrderStatus::UPDATED => 'warning',
            OrderStatus::CANCELED => 'danger',
            OrderStatus::AGREED => 'success',
            OrderStatus::PENDING => 'gray',
        };
    }
}
