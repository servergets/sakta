<?php
// app/Filament/Resources/ProductUnitResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\ProductUnitResource\Pages;
use App\Models\ProductUnit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductUnitResource extends Resource
{
    protected static ?string $model = ProductUnit::class;
    protected static ?string $navigationIcon = 'heroicon-o-scale';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 6;

    public static function canViewAny(): bool
    {
        return auth()->user()->hasPermission('product_unit.view');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('symbol')
                    ->required()
                    ->maxLength(10),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('symbol')
                    ->searchable(),
                Tables\Columns\TextColumn::make('products_count')
                    ->counts('products'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn () => auth()->user()->hasPermission('product_unit.edit')),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()->hasPermission('product_unit.delete')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->hasPermission('product_unit.delete')),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductUnits::route('/'),
            'create' => Pages\CreateProductUnit::route('/create'),
            'edit' => Pages\EditProductUnit::route('/{record}/edit'),
        ];
    }
}