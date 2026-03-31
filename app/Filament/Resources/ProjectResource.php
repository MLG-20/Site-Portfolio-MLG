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
use Filament\Forms\Components\MarkdownEditor;
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
                    ->label('Image de couverture')
                    ->image()
                    ->directory('projects')
                    ->required()
                    ->columnSpanFull(),

                FileUpload::make('gallery_images')
                    ->label('Images de galerie (slider)')
                    ->helperText('Optionnel — ces images s\'affichent dans le slider avec l\'image principale.')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->directory('projects/gallery')
                    ->nullable()
                    ->columnSpanFull(),

                FileUpload::make('video_path')
                    ->label('Vidéo de démo (MP4, MOV, WebM…)')
                    ->helperText('Optionnel — vidéo enregistrée depuis ton ordinateur, affichée dans la page du projet.')
                    ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/quicktime', 'video/x-msvideo', 'video/avi'])
                    ->directory('projects/videos')
                    ->maxSize(20480) // 20 Mo — limite PHP actuelle (upload_max_filesize)
                    ->nullable()
                    ->columnSpanFull(),

                TagsInput::make('tags')
                    ->label('Technologies (Appuie sur Entrée)')
                    ->placeholder('Laravel, PowerBI...'),

                TextInput::make('demo_link')
                    ->label('Lien Démo (URL)')
                    ->url()
                    ->placeholder('https://...'),

                TextInput::make('github_link')
                    ->label('Lien GitHub (URL)')
                    ->url()
                    ->placeholder('https://...'),

                MarkdownEditor::make('problematic')
                    ->label('Problématique / Objectif')
                    ->helperText('Quel était le problème à résoudre ?')
                    ->columnSpanFull(),

                MarkdownEditor::make('solution')
                    ->label('Ma Solution')
                    ->helperText('Comment as-tu résolu le problème ? Quelles technologies as-tu utilisées ?')
                    ->columnSpanFull(),

                MarkdownEditor::make('learnings')
                    ->label('Défis & Apprentissages')
                    ->helperText('Quelle a été la plus grande difficulté ? Qu\'as-tu appris ?')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->circular(),
                TextColumn::make('title')->sortable()->searchable(),
                TextColumn::make('tags')->badge(),
                TextColumn::make('views_count')
                    ->label('Vues')
                    ->sortable(),
                TextColumn::make('github_link')
                ->label('GitHub')
                ->icon('fab-github') // Affiche une icône GitHub
                ->url(fn (?string $state): ?string => $state) // Rend l'icône cliquable
                ->openUrlInNewTab(), // Ouvre dans un nouvel onglet
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
