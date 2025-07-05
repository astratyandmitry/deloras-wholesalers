<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WholesalerResource\Pages;
use App\Filament\Resources\WholesalerResource\RelationManagers\OrdersRelationManager;
use App\Models\Wholesaler;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class WholesalerResource extends Resource
{
    protected static ?string $model = Wholesaler::class;

    protected static ?string $navigationIcon = 'heroicon-s-user-group';

    protected static ?string $label = 'Оптовик';

    protected static ?string $pluralLabel = 'Оптовики';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Имя оптовика')
                    ->required()
                    ->maxLength(100),

                Forms\Components\TextInput::make('city')
                    ->label('Город оптовика')
                    ->required()
                    ->maxLength(100),

                Forms\Components\TextInput::make('phone')
                    ->label('Телефон оптовика')
                    ->helperText('Будет использоваться для отправки сообщений в WhatsApp')
                    ->mask('+7999999999')
                    ->unique(ignoreRecord: true)
                    ->required(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(3)
            ->schema([
                Infolists\Components\TextEntry::make('name')
                    ->label('Имя'),

                Infolists\Components\TextEntry::make('city')
                    ->label('Город'),

                Infolists\Components\TextEntry::make('phone')
                    ->label('Телефон')
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

                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),

                Tables\Columns\TextColumn::make('city')
                    ->label('Город')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Телефон')
                    ->visibleFrom('md')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            OrdersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWholesalers::route('/'),
            'create' => Pages\CreateWholesaler::route('/create'),
            'view' => Pages\ViewWholesaler::route('/{record}'),
            'edit' => Pages\EditWholesaler::route('/{record}/edit'),
        ];
    }
}
