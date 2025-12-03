<?php

namespace App\Filament\Resources\Projects;

use App\Filament\Resources\Projects\Pages\ListProjects;
use App\Filament\Resources\Projects\Schemas\ProjectForm;
use App\Filament\Resources\Projects\Schemas\DetailProjectForm;
use App\Filament\Resources\Projects\Tables\ProjectsTable;
use App\Models\Project;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string | UnitEnum | null $navigationGroup = 'Project';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    const Label = 'List Project';
    
    protected static ?string $pluralModelLabel = self::Label;
    protected static ?string $navigationLabel = self::Label; 

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 1;
    
    public static function getNavigationGroup(): ?string
    {
        return 'Project';
    }

    public static function form(Schema $schema): Schema
    {
        return ProjectForm::configure($schema);
    }

    // Gunakan DetailProjectForm untuk infolist/view
    public static function infolist(Schema $schema): Schema
    {
        return DetailProjectForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectsTable::configure($table);
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
            'index' => ListProjects::route('/'),
            // Tambahkan view page jika belum ada
            // 'view' => Pages\ViewProject::route('/{record}'),
        ];
    }
}