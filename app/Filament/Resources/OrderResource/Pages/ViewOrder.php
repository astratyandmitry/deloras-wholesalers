<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Exports\OrderExporter;
use App\Filament\Resources\OrderResource;
use App\Models\Model;
use Filament\Actions;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    public function getTitle(): string|Htmlable
    {
        return "Детали заказа: {$this->record->code}";
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make('export')
                ->icon('heroicon-s-table-cells')
                ->exporter(OrderExporter::class)
                ->columnMapping(false)
                ->formats([ExportFormat::Xlsx])
                ->modifyQueryUsing(fn(Builder $query) => $query->where('order_id', $this->record->getKey()))
                ->label('Экпортировать'),

            Actions\Action::make('fulfill-true')
                ->label('Пометить собранный')
                ->icon('heroicon-s-check')
                ->requiresConfirmation()
                ->color('success')
                ->hidden(fn(Model $record
                ): bool => $record->fulfilled || $record->items()->where('fulfilled', false)->exists())
                ->action(fn(Model $record) => $record->update(['fulfilled' => true])),

            Actions\Action::make('fulfill-false')
                ->label('Вернуть к сбору')
                ->icon('heroicon-s-no-symbol')
                ->requiresConfirmation()
                ->color('danger')
                ->hidden(fn(Model $record): bool => ! $record->fulfilled)
                ->action(fn(Model $record) => $record->update(['fulfilled' => false])),
        ];
    }
}
