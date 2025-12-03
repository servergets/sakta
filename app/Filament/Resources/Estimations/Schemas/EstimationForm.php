<?php

namespace App\Filament\Resources\Estimations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EstimationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('code')
                //     ->required(),
                TextInput::make('estimation_long')
                    ->label('Lama Estimasi')
                    ->columnSpanFull()
                    ->required()
                    ->numeric(),
                TextInput::make('name')
                    ->default('Bulan')
                    ->label('Satuan')
                    ->columnSpanFull()
                    ->readOnly()
                    ->required(),
        //         Select::make('client_id')
        //             ->relationship('client', 'name')
        //             ->required(),
        //         Select::make('project_id')
        //             ->relationship('project', 'name')
        //             ->default(null),
        //         DatePicker::make('estimation_date')
        //             ->required(),
        //         DatePicker::make('valid_until')
        //             ->required(),
        //         TextInput::make('total_amount')
        //             ->required()
        //             ->numeric(),
        //         Select::make('status')
        //             ->options([
        //     'draft' => 'Draft',
        //     'sent' => 'Sent',
        //     'approved' => 'Approved',
        //     'rejected' => 'Rejected',
        //     'expired' => 'Expired',
        // ])
        //             ->default('draft')
        //             ->required(),
        //         Textarea::make('notes')
        //             ->default(null)
        //             ->columnSpanFull(),
            ]);
    }
}
