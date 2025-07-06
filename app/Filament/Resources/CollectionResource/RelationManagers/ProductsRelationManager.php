<?php

namespace App\Filament\Resources\CollectionResource\RelationManagers;

use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

final class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $label = 'Товар';

    protected static ?string $pluralLabel = 'Товары';

    public function getTableHeading(): string
    {
        return 'Товары';
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('sku')
                    ->label('Код товара')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(40),

                Forms\Components\TextInput::make('description')
                    ->label('Название/описание товара')
                    ->required()
                    ->maxLength(1000),

                Forms\Components\TextInput::make('price_usd')
                    ->label('Стоимость товара')
                    ->prefix('$')
                    ->numeric()
                    ->required()
                    ->maxValue(100000),

                Forms\Components\FileUpload::make('image')
                    ->label('Фотография товара')
                    ->required()
                    ->image(),

                Forms\Components\Repeater::make('sizes')
                    ->label('Размеры')
                    ->relationship('sizes')
                    ->deletable(fn(Forms\Components\Repeater $component) => $component->getRecord() === null)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Название размера')
                            ->required()
                            ->maxLength(100),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('sku')
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->url(fn(Product $model) => url()->to($model->image), true)
                    ->label('Фото')
                    ->height('100px')
                    ->disk('public')
                    ->extraImgAttributes(['loading' => 'lazy']),

                Tables\Columns\TextColumn::make('sku')
                    ->description(fn(Product $item) => $item->description)
                    ->label('Код')
                    ->searchable(),

                Tables\Columns\TextColumn::make('price_usd')
                    ->label('Цена')
                    ->width('100px')
                    ->money('USD'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }
}
