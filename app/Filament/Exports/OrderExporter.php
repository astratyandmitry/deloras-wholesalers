<?php

namespace App\Filament\Exports;

use App\Models\OrderItem;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

final class OrderExporter extends Exporter
{
    protected static ?string $model = OrderItem::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('product.sku')->label('Товар'),

            ExportColumn::make('size.name')->label('Размер'),

            ExportColumn::make('quantity')
                ->label('Количество')
                ->formatStateUsing(fn(OrderItem $item) => "{$item->quantity} {$item->quantity_type->getShortLabel()}"),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Ваш заказ был успешно экспортирован, число успешных записей '.number_format($export->successful_rows).' '.str('row')->plural($export->successful_rows).'.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' записей было с ошибками.';
        }

        return $body;
    }
}
