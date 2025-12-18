<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
// ICI : Les imports qui manquaient surement
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Mes Projets';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Titre du projet')
                    ->required(),

                FileUpload::make('image')
                    ->label('Image')
                    ->image()
                    ->directory('projects') // Dossier de stockage
                    ->required()
                    ->columnSpanFull(),

                TagsInput::make('tags')
                    ->label('Technologies (Appuie sur Entrée)')
                    ->placeholder('Laravel, PowerBI...')
                    ->separator(','), // Important pour le JSON

                TextInput::make('demo_link')
                    ->label('Lien Démo (URL)')
                    ->url()
                    ->placeholder('https://...'),

                TextInput::make('github_link')
                    ->label('Lien GitHub (URL)')
                    ->url()
                    ->placeholder('https://...'),

                Textarea::make('description')
                    ->label('Description')
                    ->rows(4)
                    ->columnSpanFull()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->circular(),
                TextColumn::make('title')->sortable()->searchable(),
                TextColumn::make('tags')->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}