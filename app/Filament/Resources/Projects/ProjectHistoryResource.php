<?php

namespace App\Filament\Resources\Projects;

use App\Filament\Resources\Projects\Pages\ListProjectHistories;
use App\Filament\Resources\Projects\Schemas\ProjectForm;
use App\Filament\Resources\Projects\Schemas\ProjectInfolist;
use App\Filament\Resources\Projects\Tables\ProjectsHistoryTable;
use App\Models\Project;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use App\Filament\Widgets\OverViewWidget;

class ProjectHistoryResource extends Resource
{
    protected static ?string $model = Project::class;

    const Label = 'Riwayat Project';
    
    protected static ?string $pluralModelLabel = self::Label;
    protected static ?string $navigationLabel = self::Label; 
    protected static string | UnitEnum | null $navigationGroup = self::Label;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 1;
    
    public static function getNavigationGroup(): ?string
    { 
        return 'Riwayat Project';
    }

    public static function form(Schema $schema): Schema
    {
        return ProjectForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProjectInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectsHistoryTable::configure($table);
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
            'index' => ListProjectHistories::route('/'),
        ];
    }
    
}