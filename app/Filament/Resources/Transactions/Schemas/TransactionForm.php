<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('transaction_number')
                    ->required(),
                Select::make('sale_id')
                    ->relationship('sale', 'id')
                    ->default(null),
                Select::make('project_id')
                    ->relationship('project', 'name')
                    ->default(null),
                Select::make('payment_method_id')
                    ->relationship('paymentMethod', 'name')
                    ->required(),
                Select::make('type')
                    ->options(['income' => 'Income', 'expense' => 'Expense'])
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                DatePicker::make('transaction_date')
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }
}
