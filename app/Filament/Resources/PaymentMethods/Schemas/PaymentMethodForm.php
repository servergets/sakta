<?php

namespace App\Filament\Resources\PaymentMethods\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PaymentMethodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('bank_name')
                    ->label('Bank')
                    ->required(),
                TextInput::make('account_name')
                    ->label('Nama Akun')
                    ->required(),
                TextInput::make('account_no')
                    ->label('Nomor Rekening')
                    ->default(null),
                    // ->columnSpanFull(),
                // Toggle::make('is_active')
                //     ->required(),
            ]);
    }
}
