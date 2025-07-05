<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Models\Model;
use App\Models\OrderItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $label = 'Позиция';

    protected static ?string $pluralLabel = 'Позиции';

    public function getTableHeading(): string
    {
        return 'Позиции';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('test')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('code')
            ->columns([
                Tables\Columns\ImageColumn::make('product.image')
                    ->label('Фото')
                    ->height('100px')
                    ->url(fn(OrderItem $model) => url()->to($model->product->image), true)
                    ->disk('public')
                    ->extraImgAttributes(['loading' => 'lazy']),

                Tables\Columns\TextColumn::make('product.sku')
                    ->label('Товар')
                    ->description(fn(OrderItem $item) => $item->product->description),

                Tables\Columns\TextColumn::make('size.name')
                    ->label('Размер')
                    ->width('200px'),

                Tables\Columns\TextColumn::make('quantity')
                    ->formatStateUsing(fn(OrderItem $item
                    ): string => "{$item->quantity} {$item->quantity_type->getShortLabel()}")
                    ->badge()
                    ->color('gray')
                    ->label('Количество')
                    ->width('150px'),

                Tables\Columns\ToggleColumn::make('fulfilled')
                    ->label('Собран')
                    ->width('100px'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('fulfilled')
                    ->label('Собран'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('fulfill-true')
                        ->label('Товары собран')
                        ->icon('heroicon-s-check')
                        ->requiresConfirmation()
                        ->action(fn(Collection $records) => $records->each->update(['fulfilled' => true])),

                    Tables\Actions\BulkAction::make('fulfill-false')
                        ->label('Вернуть на сборку')
                        ->icon('heroicon-s-no-symbol')
                        ->requiresConfirmation()
                        ->action(fn(Collection $records) => $records->each->update(['fulfilled' => false])),
                ]),
            ]);
    }
}
