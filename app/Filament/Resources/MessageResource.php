<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MessageResource\Pages;
use App\Models\Message;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class MessageResource extends Resource
{
    protected static ?string $model = Message::class;
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'Messages';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $count = Message::whereNull('read_at')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->label('Nom')->disabled(),
            Forms\Components\TextInput::make('email')->label('Email')->disabled(),
            Forms\Components\TextInput::make('phone')->label('Téléphone')->disabled(),
            Forms\Components\TextInput::make('subject')->label('Sujet')->disabled()->columnSpanFull(),
            Forms\Components\Textarea::make('message')->label('Message')->disabled()->rows(6)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                IconColumn::make('read_at')
                    ->label('')
                    ->boolean()
                    ->trueIcon('heroicon-o-envelope-open')
                    ->falseIcon('heroicon-o-envelope')
                    ->trueColor('gray')
                    ->falseColor('danger')
                    ->tooltip(fn ($record) => $record->read_at ? 'Lu' : 'Non lu'),

                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->weight(fn ($record) => $record->read_at ? 'normal' : 'bold'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('subject')
                    ->label('Sujet')
                    ->limit(40)
                    ->weight(fn ($record) => $record->read_at ? 'normal' : 'bold'),

                TextColumn::make('created_at')
                    ->label('Reçu le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('non_lus')
                    ->label('Non lus')
                    ->query(fn ($query) => $query->whereNull('read_at')),
            ])
            ->actions([
                Tables\Actions\Action::make('marquer_lu')
                    ->label('Marquer lu')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->hidden(fn ($record) => $record->read_at !== null)
                    ->action(fn ($record) => $record->markAsRead()),

                Tables\Actions\ViewAction::make()
                    ->after(fn ($record) => $record->isRead() ?: $record->markAsRead()),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('marquer_lus')
                        ->label('Marquer comme lus')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn ($records) => $records->each->markAsRead()),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMessages::route('/'),
            'view'  => Pages\ViewMessage::route('/{record}'),
        ];
    }
}
