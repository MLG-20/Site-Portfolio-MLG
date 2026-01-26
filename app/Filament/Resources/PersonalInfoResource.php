<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PersonalInfoResource\Pages;
use App\Models\PersonalInfo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\Textarea;

class PersonalInfoResource extends Resource
{
    protected static ?string $model = PersonalInfo::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Infos Perso';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nom Complet')
                    ->required(),
                
                // C'est ici que tu gères l'animation Typed.js !
                TagsInput::make('titles')
                    ->label('Titres animés (Appuie sur Entrée)')
                    ->placeholder('Ajouter un titre')
                    ->separator(','),
                    // ---- AJOUTE CE BLOC DE CODE ICI ----
                Textarea::make('description')->rows(5)->columnSpanFull(),

                TextInput::make('email')->email()->required(),
                TextInput::make('phone')->tel(),
                
                FileUpload::make('profile_image')
                    ->label('Photo de Profil')
                    ->image()
                    ->directory('profile') // Sera stocké dans storage/app/public/profile
                    ->columnSpanFull(),

                FileUpload::make('cv_path')
                    ->label('Fichier CV (PDF)')
                    ->acceptedFileTypes(['application/pdf'])
                    ->directory('cv')
                    ->columnSpanFull(),

                TextInput::make('linkedin')
                    ->label('Lien LinkedIn')
                    ->url()
                    ->prefix('https://'),
                
                TextInput::make('github')
                    ->label('Lien GitHub')
                    ->url()
                    ->prefix('https://'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('profile_image')->circular(),
                TextColumn::make('name')->label('Nom'),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('titles')->label('Titres')->badge(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPersonalInfos::route('/'),
            'edit' => Pages\EditPersonalInfo::route('/{record}/edit'),
        ];
    }
}
