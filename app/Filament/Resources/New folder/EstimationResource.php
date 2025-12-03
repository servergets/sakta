<?php
// app/Filament/Resources/EstimationResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\EstimationResource\Pages;
use App\Models\Estimation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EstimationResource extends Resource
{
    protected static ?string $model = Estimation::class;
    protected static ?string $navigationIcon = 'heroicon-o-calculator';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 7;

    public static function canViewAny(): bool
    {
        return auth()->user()->hasPermission('estimation.view');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('client_id')
                    ->relationship('client', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('project_id')
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\DatePicker::make('estimation_date')
                    ->required()
                    ->default(now()),
                Forms\Components\DatePicker::make('valid_until')
                    ->required()
                    ->default(now()->addDays(30)),
                Forms\Components\Repeater::make('estimationItems')
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
                Forms\Components\TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->prefix('
                '),
                Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'sent' => 'Sent',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'expired' => 'Expired',
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
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('client.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('estimation_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('valid_until')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'draft',
                        'warning' => 'sent',
                        'success' => 'approved',
                        'danger' => 'rejected',
                        'gray' => 'expired',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('client_id')
                    ->relationship('client', 'name'),
                Tables\Filters\SelectFilter::make('project_id')
                    ->relationship('project', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'sent' => 'Sent',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'expired' => 'Expired',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(fn () => auth()->user()->hasPermission('estimation.view')),
                Tables\Actions\EditAction::make()
                    ->visible(fn () => auth()->user()->hasPermission('estimation.edit')),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()->hasPermission('estimation.delete')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->hasPermission('estimation.delete')),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEstimations::route('/'),
            'create' => Pages\CreateEstimation::route('/create'),
            'view' => Pages\ViewEstimation::route('/{record}'),
            'edit' => Pages\EditEstimation::route('/{record}/edit'),
        ];
    }
}