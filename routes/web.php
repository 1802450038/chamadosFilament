<?php

use App\Livewire\CustomProfileComponent;
use App\Livewire\ListActiveCalls;
use App\Livewire\ListCalls;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});




Route::get('painel',CustomProfileComponent::class);

