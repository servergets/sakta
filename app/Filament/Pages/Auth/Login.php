<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Component; 
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;

class Login extends BaseLogin
{
    protected string $view = 'filament.pages.auth.login';
    
    public function form(Schema $schema): Schema
    {
        return $schema->components([
            $this->getEmailFormComponent(),
            $this->getPasswordFormComponent(),
            $this->getRememberFormComponent(),
        ]);
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Username')
            ->placeholder('Admin')
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Kata Sandi')
            ->placeholder('*******')
            ->password()
            ->revealable(true)
            ->required()
            ->extraInputAttributes(['tabindex' => 2]);
    }

    protected function getRememberFormComponent(): Component
    {
        // return \Filament\Forms\Components\Checkbox::make('remember')
        //     ->label('Ingatkan Saya')
        //     ->extraInputAttributes(['tabindex' => 3]);
        
        
        return ViewField::make('remember_and_forgot')
            ->view('filament.pages.auth.components.remember-and-forgot') // ini view custom, aman dari loop
            ->columnSpanFull();
    }
}