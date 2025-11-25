<?php

use App\Http\Controllers\TypeTournamentController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlacesController;
use Illuminate\Support\Facades\Route;

//rutas de administrador
Route::get('/dashboard', [DashboardController::class, 'dashboard'])->middleware(['auth', 'role:admin', 'verified'])->name('dashboard');

Route::middleware('auth', 'role:admin', 'verified')->group(function(){
    Route::get('/tournaments', [TournamentController::class, 'index'])->name('tournaments.index');
    Route::get('/tournaments/create', [TournamentController::class, 'create'])->name('tournaments.create');
    Route::post('/tournaments', [TournamentController::class, 'store'])->name('tournaments.store');
    Route::get('/tournaments/{tournament}', [TournamentController::class, 'show'])->name('tournaments.show');
    Route::get('/tournaments/{tournament}/edit', [TournamentController::class, 'edit'])->name('tournaments.edit');
    Route::get('/tournaments/{tournament}/winner', [TournamentController::class, 'winner'])->name('tournaments.winner');
    Route::put('/tournaments/winner/{tournament}', [TournamentController::class, 'storeWinner'])->name('tournaments.winnerStore');
    Route::put('/tournaments/{tournament}', [TournamentController::class, 'update'])->name('tournaments.update');
    Route::delete('/tournaments/{tournament}', [TournamentController::class, 'destroy'])->name('tournaments.destroy');

});

Route::middleware('auth', 'role:admin', 'verified')->group(function(){
    Route::get('/type-tournaments', [TypeTournamentController::class, 'index'])->name('typeTournaments.index');
    Route::get('/type-tournaments/create', [TypeTournamentController::class, 'create'])->name('typeTournaments.create');
    Route::post('/type-tournaments', [TypeTournamentController::class, 'store'])->name('typeTournaments.store');
    Route::get('/type-tournaments/{typeTournament}', [TypeTournamentController::class, 'show'])->name('typeTournaments.show');
    Route::get('/type-tournaments/{typeTournament}/edit', [TypeTournamentController::class, 'edit'])->name('typeTournaments.edit');
    Route::put('/type-tournaments/{typeTournament}', [TypeTournamentController::class, 'update'])->name('typeTournaments.update');
    Route::delete('/type-tournaments/{typeTournament}', [TypeTournamentController::class, 'destroy'])->name('typeTournaments.destroy');
});

Route::middleware('auth', 'role:admin', 'verified')->group(function(){
    Route::get('/memberships', [MembershipController::class, 'index'])->name('memberships.index');
    Route::get('/memberships/create', [MembershipController::class, 'create'])->name('memberships.create');
    Route::post('/memberships', [MembershipController::class, 'store'])->name('memberships.store');
    Route::get('/memberships/{membership}', [MembershipController::class, 'show'])->name('memberships.show');
    Route::get('/memberships/{membership}/edit', [MembershipController::class, 'edit'])->name('memberships.edit');
    Route::put('/memberships/{membership}', [MembershipController::class, 'update'])->name('memberships.update');
    Route::delete('/memberships/{membership}', [MembershipController::class, 'destroy'])->name('memberships.destroy');
});

Route::middleware('auth', 'role:admin', 'verified')->group(function(){
    Route::get('/places/{tournament}', [PlacesController::class, 'index'])->name('places.index');
    Route::get('/places/{place}', [PlacesController::class, 'show'])->name('places.show');
    Route::get('/places/{place}/edit', [PlacesController::class, 'edit'])->name('places.edit');
    Route::put('/places/{place}', [PlacesController::class, 'update'])->name('places.update');
    Route::get('/places/{place}/reschedule', [PlacesController::class, 'reschedule'])->name('places.reschedule');
    Route::put('/places/reschedule/{place}', [PlacesController::class, 'storeReschedule'])->name('places.storeReschedule');
});