<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Enums\OrderStatus;
use App\Models\Order;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\HtmlString;

final class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    public function getTitle(): string|HtmlString
    {
        return "Детали заказа: {$this->record->code}";
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('preview')
                ->label('Накладная')
                ->color('gray')
                ->icon('heroicon-s-table-cells')
                ->url(fn(Order $order) => route('order.preview', $order), true),

            Actions\Action::make('change-status')
                ->label('Изменить статус')
                ->form([
                    Select::make('status')
                        ->label('Статуст')
                        ->options(OrderStatus::class)
                        ->default(fn(Order $record) => $record->status)
                        ->required(),
                ])
                ->action(function (array $data, Order $record): void {
                    $record->update(['status' => $data['status']]);
                })
                ->hidden(fn(Order $record) => $record->status !== OrderStatus::PENDING),
        ];
    }
}
