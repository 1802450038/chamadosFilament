<?php


use App\Livewire\ListActiveCalls;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});




Route::get('painel',ListActiveCalls::class);

