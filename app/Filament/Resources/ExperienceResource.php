<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExperienceResource\Pages;
use App\Models\Experience;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class ExperienceResource extends Resource
{
    protected static ?string $model = Experience::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Expériences';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Poste / Diplôme')
                    ->required()
                    ->placeholder('Ex: Développeur PHP'),

                TextInput::make('company')
                    ->label('Entreprise / École')
                    ->placeholder('Ex: Université de Thiès'),

                TextInput::make('duration')
                    ->label('Durée / Dates')
                    ->required()
                    ->placeholder('Ex: 2023 - Présent'),

                TextInput::make('icon')
                    ->label('Icône FontAwesome')
                    ->default('fa-solid fa-briefcase')
                    ->placeholder('fa-solid fa-code')
                    ->helperText('Cherche les codes sur fontawesome.com'),

                Textarea::make('description')
                    ->rows(3)
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('certificate_path')
                    ->label('Diplôme / Certificat')
                    ->helperText('Image (JPG, PNG) ou PDF — s\'affiche en popup sur le portfolio')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf'])
                    ->directory('certificates')
                    ->columnSpanFull(),

                Forms\Components\Select::make('document_type')
                    ->label('Type de document')
                    ->options([
                        'certificat' => 'Certificat',
                        'diplome'    => 'Diplôme',
                    ])
                    ->default('certificat')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Poste')->searchable()->sortable(),
                TextColumn::make('company')->label('Structure'),
                TextColumn::make('duration')->label('Date'),
                Tables\Columns\IconColumn::make('certificate_path')
                    ->label('Certificat')
                    ->boolean()
                    ->trueIcon('heroicon-o-document-check')
                    ->falseIcon('heroicon-o-minus'),
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

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExperiences::route('/'),
            'create' => Pages\CreateExperience::route('/create'),
            'edit' => Pages\EditExperience::route('/{record}/edit'),
        ];
    }
}