<?php

namespace App\Filament\Resources\Estimations;

use App\Filament\Resources\Estimations\Pages\CreateEstimation;
use App\Filament\Resources\Estimations\Pages\EditEstimation;
use App\Filament\Resources\Estimations\Pages\ListEstimations;
use App\Filament\Resources\Estimations\Pages\ViewEstimation;
use App\Filament\Resources\Estimations\Schemas\EstimationForm;
use App\Filament\Resources\Estimations\Schemas\EstimationInfolist;
use App\Filament\Resources\Estimations\Tables\EstimationsTable;
use App\Filament\Clusters\Settings\SettingsCluster as ClustersSettings;
use App\Models\Estimation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EstimationResource extends Resource
{
    protected static ?string $model = Estimation::class;
    protected static ?string $cluster = ClustersSettings::class;
    
    const Label = 'Estimasi';
    
    protected static ?string $pluralModelLabel = self::Label;
    protected static ?string $navigationLabel = self::Label; 
    protected static ?int $navigationSort = 50;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return EstimationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EstimationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EstimationsTable::configure($table);
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
            'index' => ListEstimations::route('/'),
            // 'create' => CreateEstimation::route('/create'),
            // 'view' => ViewEstimation::route('/{record}'),
            // 'edit' => EditEstimation::route('/{record}/edit'),
        ];
    }
}
