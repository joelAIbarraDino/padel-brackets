<?php

use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TypeTournamentController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth', 'verified')->group(function(){
    Route::get('/tournaments', [TournamentController::class, 'index'])->name('tournaments.index');
    Route::get('/tournaments/create', [TournamentController::class, 'create'])->name('tournaments.create');
    Route::post('/tournaments', [TournamentController::class, 'store'])->name('tournaments.store');
    Route::get('/tournaments/{tournament}', [TournamentController::class, 'show'])->name('tournaments.show');
    Route::get('/tournaments/{tournament}/edit', [TournamentController::class, 'edit'])->name('tournaments.edit');
    Route::put('/tournaments/{tournament}', [TournamentController::class, 'update'])->name('tournaments.update');
    Route::delete('/tournaments/{tournament}', [TournamentController::class, 'destroy'])->name('tournaments.destroy');

});

Route::middleware('auth', 'verified')->group(function(){
    Route::get('/type-tournaments', [TypeTournamentController::class, 'index'])->name('typeTournaments.index');
    Route::get('/type-tournaments/create', [TypeTournamentController::class, 'create'])->name('typeTournaments.create');
    Route::post('/type-tournaments', [TypeTournamentController::class, 'store'])->name('typeTournaments.store');
    Route::get('/type-tournaments/{typeTournament}', [TypeTournamentController::class, 'show'])->name('typeTournaments.show');
    Route::get('/type-tournaments/{typeTournament}/edit', [TypeTournamentController::class, 'edit'])->name('typeTournaments.edit');
    Route::put('/type-tournaments/{typeTournament}', [TypeTournamentController::class, 'update'])->name('typeTournaments.update');
    Route::delete('/type-tournaments/{typeTournament}', [TypeTournamentController::class, 'destroy'])->name('typeTournaments.destroy');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
