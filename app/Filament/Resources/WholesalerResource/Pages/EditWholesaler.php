<?php

namespace App\Filament\Resources\WholesalerResource\Pages;

use App\Filament\Resources\WholesalerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditWholesaler extends EditRecord
{
    protected static string $resource = WholesalerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }

    public function getRelationManagers(): array
    {
        return [];
    }
}
