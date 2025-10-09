<?php

use App\Livewire\CustomProfileComponent;
use App\Livewire\ListActiveCalls;
use App\Livewire\ListCalls;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});




Route::get('painel',CustomProfileComponent::class);

