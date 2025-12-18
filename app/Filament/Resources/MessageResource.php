<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MessageResource\Pages;
use App\Models\Message;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class MessageResource extends Resource
{
    protected static ?string $model = Message::class;
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'Messagerie';

    // On met tout en lecture seule (readOnly) car on ne modifie pas un message reçu
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nom')->readOnly(),
                TextInput::make('email')->label('Email')->readOnly(),
                TextInput::make('phone')->label('Téléphone')->readOnly(),
                TextInput::make('created_at')->label('Reçu le')->readOnly(),
                
                TextInput::make('subject')
                    ->label('Sujet')
                    ->columnSpanFull()
                    ->readOnly(),

                Textarea::make('message')
                    ->label('Contenu du message')
                    ->columnSpanFull()
                    ->readOnly(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->label('Date')
                    ->sortable(),
                TextColumn::make('name')->label('Nom')->searchable(),
                TextColumn::make('subject')->label('Sujet'),
                TextColumn::make('email')->icon('heroicon-m-envelope'),
            ])
            ->defaultSort('created_at', 'desc') // Les plus récents en premier
            ->actions([
                Tables\Actions\ViewAction::make(), // Bouton "Voir"
                Tables\Actions\DeleteAction::make(), // Bouton "Supprimer"
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMessages::route('/'),
            // On retire 'create' car on ne crée pas de messages depuis l'admin
        ];
    }
}