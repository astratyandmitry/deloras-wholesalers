<?php

namespace App\Filament\Resources\WholesalerResource\RelationManagers;

use App\Filament\Resources\OrderResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

final class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    protected static ?string $label = 'Заказ';

    protected static ?string $pluralLabel = 'Заказы';

    public function getTableHeading(): string
    {
        return 'Заказы';
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product.name')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->width('50px'),

                Tables\Columns\TextColumn::make('collection.name')
                    ->label('Коллекция'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата заказа')
                    ->dateTime()
                    ->width('150px'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn($record) => OrderResource::getUrl('view', ['record' => $record]))
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('id', 'desc');
    }
}
