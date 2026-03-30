<?php

namespace App\Filament\Widgets;

use App\Models\Message;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentMessages extends BaseWidget
{
    protected static ?string $heading = 'Derniers messages';
    protected static ?int $sort = 99;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Message::query()->latest()->limit(5))
            ->columns([
                Tables\Columns\IconColumn::make('read_at')
                    ->label('')
                    ->boolean()
                    ->trueIcon('heroicon-o-envelope-open')
                    ->falseIcon('heroicon-o-envelope')
                    ->trueColor('gray')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->weight(fn ($record) => $record->read_at ? 'normal' : 'bold'),

                Tables\Columns\TextColumn::make('subject')
                    ->label('Sujet')
                    ->limit(50)
                    ->weight(fn ($record) => $record->read_at ? 'normal' : 'bold'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Reçu le')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->actions([
                Tables\Actions\Action::make('voir')
                    ->label('Voir')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => route('filament.admin.resources.messages.view', $record)),
            ]);
    }
}
