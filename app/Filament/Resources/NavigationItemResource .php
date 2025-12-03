<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NavigationItemResource\Pages;
use App\Models\NavigationItem;
use App\Models\NavigationGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NavigationItemResource extends Resource
{
    protected static ?string $model = NavigationItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $navigationLabel = 'Navigation';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 99;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Name (Unique Key)'),

                        Forms\Components\TextInput::make('label')
                            ->required()
                            ->maxLength(255)
                            ->label('Display Label'),

                        Forms\Components\TextInput::make('url')
                            ->required()
                            ->maxLength(255)
                            ->prefix('/')
                            ->placeholder('admin/projects'),

                        Forms\Components\TextInput::make('icon')
                            ->maxLength(255)
                            ->placeholder('heroicon-o-folder')
                            ->helperText('Heroicon name (e.g., heroicon-o-folder)'),
                    ])->columns(2),

                Forms\Components\Section::make('Organization')
                    ->schema([
                        Forms\Components\Select::make('navigation_group_id')
                            ->label('Navigation Group')
                            ->options(NavigationGroup::pluck('label', 'id'))
                            ->searchable()
                            ->nullable()
                            ->helperText('Leave empty for single item'),

                        Forms\Components\Select::make('parent_id')
                            ->label('Parent Item (for sub-menu)')
                            ->options(NavigationItem::whereNull('parent_id')->pluck('label', 'id'))
                            ->searchable()
                            ->nullable(),

                        Forms\Components\TextInput::make('sort')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower number = higher position. Use negative for top items.'),

                        Forms\Components\TextInput::make('resource')
                            ->maxLength(255)
                            ->placeholder('App\Filament\Resources\ProjectResource')
                            ->helperText('Optional: Link to Filament Resource class'),
                    ])->columns(2),

                Forms\Components\Section::make('Badge & Visibility')
                    ->schema([
                        Forms\Components\TextInput::make('badge')
                            ->maxLength(50)
                            ->placeholder('New, 5, etc'),

                        Forms\Components\Select::make('badge_color')
                            ->options([
                                'primary' => 'Primary',
                                'success' => 'Success',
                                'danger' => 'Danger',
                                'warning' => 'Warning',
                                'info' => 'Info',
                                'gray' => 'Gray',
                            ]),

                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->label('Active'),

                        Forms\Components\Toggle::make('visible')
                            ->default(true)
                            ->label('Visible'),

                        Forms\Components\Toggle::make('is_group')
                            ->default(false)
                            ->label('Is Group Header'),
                    ])->columns(3),

                Forms\Components\Section::make('Permissions')
                    ->schema([
                        Forms\Components\TagsInput::make('roles')
                            ->placeholder('admin, manager, user')
                            ->helperText('User must have one of these roles to see this item. Leave empty for all users.'),

                        Forms\Components\TagsInput::make('permissions')
                            ->placeholder('view_projects, edit_projects')
                            ->helperText('Required permissions (if using permission system)'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort')
                    ->sortable()
                    ->width(60),

                Tables\Columns\TextColumn::make('group.label')
                    ->label('Group')
                    ->sortable()
                    ->searchable()
                    ->placeholder('No Group'),

                Tables\Columns\TextColumn::make('label')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('icon')
                    ->icon(fn ($record) => $record->icon)
                    ->placeholder('No Icon'),

                Tables\Columns\TextColumn::make('url')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('badge')
                    ->badge()
                    ->color(fn ($record) => $record->badge_color ?? 'gray'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),

                Tables\Columns\IconColumn::make('visible')
                    ->boolean(),
            ])
            ->defaultSort('sort')
            ->filters([
                Tables\Filters\SelectFilter::make('navigation_group_id')
                    ->label('Group')
                    ->options(NavigationGroup::pluck('label', 'id')),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
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
            ->reorderable('sort');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNavigationItems::route('/'),
            'create' => Pages\CreateNavigationItem::route('/create'),
            'edit' => Pages\EditNavigationItem::route('/{record}/edit'),
        ];
    }
}