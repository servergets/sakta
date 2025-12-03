<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Pages\Auth\Login;

Route::get('/', function () {
    return view('welcome');
});
