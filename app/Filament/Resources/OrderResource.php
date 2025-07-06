<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Wholesaler;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

final class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-s-shopping-cart';

    protected static ?string $label = 'Заказ';

    protected static ?string $pluralLabel = 'Заказы';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(2)
            ->schema([
                Infolists\Components\TextEntry::make('collection.name')
                    ->label('Коллекция'),

                Infolists\Components\TextEntry::make('wholesaler.label')
                    ->label('Оптовик'),

                Infolists\Components\TextEntry::make('status')
                    ->badge()
                    ->label('Статус'),

                Infolists\Components\TextEntry::make('created_at')
                    ->label('Дата заказа')
                    ->dateTime(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->width('50px'),

                Tables\Columns\TextColumn::make('code')
                    ->label('Код')
                    ->width('80px')
                    ->searchable(),

                Tables\Columns\TextColumn::make('wholesaler.label')
                    ->label('Оптовик'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->label('Статус')
                    ->width('150px'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создано')
                    ->width('200px')
                    ->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(OrderStatus::class)
                    ->label('Статус'),

                Tables\Filters\SelectFilter::make('collection_id')
                    ->label('Коллекция')
                    ->relationship('collection', 'name'),

                Tables\Filters\SelectFilter::make('wholesaler_id')
                    ->label('Оптовик')
                    ->getOptionLabelFromRecordUsing(fn(Wholesaler $record) => $record->label)
                    ->relationship('wholesaler', 'name')
                    ->searchable(['name', 'city', 'phone'])
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }
}
