<?php
// app/Filament/Resources/SaleResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Models\Sale;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 4;

    public static function canViewAny(): bool
    {
        return auth()->user()->hasPermission('sale.view');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('invoice_number')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Select::make('buyer_id')
                    ->relationship('buyer', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('project_id')
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\DatePicker::make('sale_date')
                    ->required()
                    ->default(now()),
                Forms\Components\Repeater::make('saleItems')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->relationship('product', 'name')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $product = \App\Models\Product::find($state);
                                if ($product) {
                                    $set('unit_price', $product->price);
                                }
                            }),
                        Forms\Components\TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $unitPrice = $get('unit_price');
                                $set('total_price', $state * $unitPrice);
                            }),
                        Forms\Components\TextInput::make('unit_price')
                            ->required()
                            ->numeric()
                            ->prefix('
                ')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $quantity = $get('quantity');
                                $set('total_price', $quantity * $state);
                            }),
                        Forms\Components\TextInput::make('total_price')
                            ->required()
                            ->numeric()
                            ->prefix('
                ')
                            ->disabled(),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->prefix('
                '),
                Forms\Components\TextInput::make('tax_amount')
                    ->numeric()
                    ->prefix('
                ')
                    ->default(0),
                Forms\Components\TextInput::make('discount_amount')
                    ->numeric()
                    ->prefix('
                ')
                    ->default(0),
                Forms\Components\TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->prefix('
                '),
                Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'confirmed' => 'Confirmed',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('draft')
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('buyer.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sale_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'draft',
                        'warning' => 'confirmed',
                        'success' => 'paid',
                        'danger' => 'cancelled',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('buyer_id')
                    ->relationship('buyer', 'name'),
                Tables\Filters\SelectFilter::make('project_id')
                    ->relationship('project', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'confirmed' => 'Confirmed',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(fn () => auth()->user()->hasPermission('sale.view')),
                Tables\Actions\EditAction::make()
                    ->visible(fn () => auth()->user()->hasPermission('sale.edit')),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()->hasPermission('sale.delete')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->hasPermission('sale.delete')),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'view' => Pages\ViewSale::route('/{record}'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }
}