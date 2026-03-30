<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TechnologyResource\Pages;
use App\Models\Technology;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TechnologyResource extends Resource
{
    protected static ?string $model = Technology::class;

    protected static ?string $navigationIcon = 'heroicon-o-code-bracket';
    protected static ?string $navigationLabel = 'Technologies';
    protected static ?string $navigationGroup = 'Portfolio';
    protected static ?int $navigationSort = 3;

    /** Liste des technos disponibles : slug SVG → nom affiché */
    public static array $technoList = [
        // Personnalisé (image uploadée)
        '_custom'                           => ['name' => '— Image personnalisée —'],
        // Backend
        'php/php-original'                  => ['name' => 'PHP'],
        'laravel/laravel-original'          => ['name' => 'Laravel'],
        'python/python-original'            => ['name' => 'Python'],
        'nodejs/nodejs-plain'               => ['name' => 'Node.js'],
        'django/django-plain'               => ['name' => 'Django'],
        'fastapi/fastapi-original'          => ['name' => 'FastAPI'],
        'mysql/mysql-original'              => ['name' => 'MySQL'],
        'postgresql/postgresql-plain'       => ['name' => 'PostgreSQL'],
        'mongodb/mongodb-plain'             => ['name' => 'MongoDB'],
        'redis/redis-plain'                 => ['name' => 'Redis'],
        // Frontend
        'javascript/javascript-plain'       => ['name' => 'JavaScript'],
        'typescript/typescript-plain'       => ['name' => 'TypeScript'],
        'angularjs/angularjs-plain'         => ['name' => 'Angular'],
        'react/react-original'              => ['name' => 'React'],
        'vuejs/vuejs-plain'                 => ['name' => 'Vue.js'],
        'css3/css3-plain'                   => ['name' => 'CSS'],
        'html5/html5-plain'                 => ['name' => 'HTML'],
        'bootstrap/bootstrap-plain'         => ['name' => 'Bootstrap'],
        'tailwindcss/tailwindcss-original'  => ['name' => 'TailwindCSS'],
        // Analyse de données
        'pandas/pandas-original'            => ['name' => 'Pandas'],
        'numpy/numpy-original'              => ['name' => 'NumPy'],
        'scikitlearn/scikitlearn-original'  => ['name' => 'Scikit-learn'],
        'jupyter/jupyter-original'          => ['name' => 'Jupyter Notebook'],
        'streamlit/streamlit-original'      => ['name' => 'Streamlit'],
        // Outils
        'git/git-plain'                     => ['name' => 'Git'],
        'github/github-original'            => ['name' => 'GitHub'],
        'docker/docker-plain'               => ['name' => 'Docker'],
        'linux/linux-plain'                 => ['name' => 'Linux'],
        'vscode/vscode-plain'               => ['name' => 'VS Code'],
        'figma/figma-plain'                 => ['name' => 'Figma'],
        'postman/postman-plain'             => ['name' => 'Postman'],
    ];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations')
                    ->schema([
                        Forms\Components\Select::make('devicon_slug')
                            ->label('Technologie')
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state !== '_custom') {
                                    $set('name', self::$technoList[$state]['name'] ?? '');
                                }
                            })
                            ->options(fn () => collect(self::$technoList)->mapWithKeys(
                                fn ($item, $slug) => [$slug => $item['name']]
                            )->toArray())
                            ->helperText('Sélectionne la technologie — ou choisis "Image personnalisée" pour Plotly, Streamlit, etc.'),

                        Forms\Components\TextInput::make('name')
                            ->label('Nom affiché')
                            ->placeholder('ex: Laravel')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Modifiable si tu veux un nom différent'),

                        Forms\Components\Select::make('category')
                            ->label('Catégorie')
                            ->options([
                                'Frontend'     => 'Frontend',
                                'Backend'      => 'Backend',
                                'Analyse de données' => 'Analyse de données',
                                'Outils'       => 'Outils',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('order')
                            ->label('Ordre d\'affichage')
                            ->numeric()
                            ->default(0)
                            ->helperText('0 = premier, 1 = deuxième, etc.'),

                        Forms\Components\FileUpload::make('custom_image')
                            ->label('Image personnalisée (optionnel)')
                            ->helperText('Pour les technos absentes de Devicon (ex: Plotly, Streamlit). Remplace l\'icône automatique.')
                            ->image()
                            ->directory('technologies')
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Visible sur le portfolio')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('devicon_slug')
                    ->label('Slug Devicon')
                    ->searchable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Catégorie')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Frontend'     => 'info',
                        'Backend'      => 'success',
                        'Analyse de données' => 'danger',
                        'Outils'       => 'warning',
                        default        => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('order')
                    ->label('Ordre')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Visible')
                    ->boolean(),
            ])
            ->defaultSort('category')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Catégorie')
                    ->options([
                        'Frontend'     => 'Frontend',
                        'Backend'      => 'Backend',
                        'Analyse de données' => 'Analyse de données',
                        'Outils'       => 'Outils',
                    ]),
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
            'index' => Pages\ListTechnologies::route('/'),
            'create' => Pages\CreateTechnology::route('/create'),
            'edit' => Pages\EditTechnology::route('/{record}/edit'),
        ];
    }
}
