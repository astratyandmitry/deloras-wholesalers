<?php

namespace App\Filament\Resources\WholesalerResource\Pages;

use App\Filament\Resources\WholesalerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewWholesaler extends ViewRecord
{
    protected static string $resource = WholesalerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return "Детали оптовика";
    }
}
