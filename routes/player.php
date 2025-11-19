<?php

use App\Http\Controllers\PlayerController;
use Illuminate\Support\Facades\Route;

//rutas de jugadores
Route::get('/player', [PlayerController::class, 'dashboard'])->middleware('auth', 'role:user', 'verified')->name('player');