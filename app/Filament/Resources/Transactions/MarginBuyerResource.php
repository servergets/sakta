<?php

namespace App\Filament\Resources\Transactions;

use App\Filament\Resources\Transactions\Pages\ListMarginBuyer;
use App\Filament\Resources\Transactions\Schemas\TransactionProjectForm;
use App\Filament\Resources\Transactions\Tables\MarginBuyerTable;
use App\Models\Project;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MarginBuyerResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $modelLabel = 'Margin Buyer';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationLabel = 'Margin Buyer';

    public static function form(Schema $schema): Schema
    {
        return TransactionProjectForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MarginBuyerTable::configure($table);
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
            'index' => ListMarginBuyer::route('/'),
        ];
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['total_amount_raw'])) {
            $data['total_amount'] = $data['total_amount_raw'];
            unset($data['total_amount_raw']);
        }
        
        return $data;
    }
}
