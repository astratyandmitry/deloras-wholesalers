<?php

namespace App\Filament\Resources\WholesalerResource\Pages;

use App\Filament\Resources\WholesalerResource;
use App\Models\Collection;
use App\Models\Invitation;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms;
use Illuminate\Contracts\Support\Htmlable;

final class ViewWholesaler extends ViewRecord
{
    protected static string $resource = WholesalerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('sendToWhatsApp')
                ->label('Отправить в WhatsApp')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->form([
                    Forms\Components\Select::make('collection_id')
                        ->label('Какую коллекцию отправить?')
                        ->options(Collection::all()->pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data) {
                    $invitation = Invitation::make($this->record->id, $data['collection_id']);

                    $this->redirect($invitation->whatsapp_url);
                })
                ->modalHeading('Отправить в WhatsApp')
                ->modalSubmitActionLabel('Отправить')
                ->requiresConfirmation(false),

            Actions\EditAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return "Детали оптовика";
    }
}
