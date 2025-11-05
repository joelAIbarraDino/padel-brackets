<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\TypeTournamentController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\PlacesController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;
use App\Models\Tournament;
use Inertia\Inertia;
use Carbon\Carbon;

Route::get('/', function () {
    $now = Carbon::now();

    $startOfMonth = $now->copy()->startOfMonth();
    $endOfMonth = $now->copy()->endOfMonth();

    $tournaments = Tournament::whereBetween('scheduled_event', [$startOfMonth, $endOfMonth])
        ->get()->map(function($tournament){
            return[
                'title'=>'Torneo #'.$tournament->id,
                'start'=>$tournament->scheduled_event,
                'url'=>'/torneos/places/'.$tournament->id
            ];
        });




    return Inertia::render('Home', [
        'events'=>$tournaments
    ]);
    })->name('home');

Route::get('/torneos', function(){
    $now = Carbon::now();

    $startOfMonth = $now->copy()->startOfMonth();
    $endOfMonth = $now->copy()->endOfMonth();

    $tournaments = Tournament::whereBetween('scheduled_event', [$startOfMonth, $endOfMonth])
        ->get()->map(function($tournament){
            return[
                'title'=>'Torneo #'.$tournament->id,
                'start'=>$tournament->scheduled_event,
                'url'=>'/torneos/places/'.$tournament->id
            ];
        });


    return Inertia::render('public/tournaments', [
        'events'=>$tournaments
    ]);
    
})->name('tournaments');

Route::get('/torneos/places/{tournament}', function(Tournament $tournament){
    return Inertia::render('public/places', [
        'places'=>$tournament->places()->with('user:id,name')->get()
    ]);
});

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


Route::middleware('auth', 'verified')->group(function(){
    Route::get('/places/{tournament}', [PlacesController::class, 'index'])->name('places.index');
    Route::get('/places/{place}', [PlacesController::class, 'show'])->name('places.show');
    Route::get('/places/{place}/edit', [PlacesController::class, 'edit'])->name('places.edit');
    Route::put('/places/{place}', [PlacesController::class, 'update'])->name('places.update');
});

Route::middleware(['web'])->group(function () {
    Route::get('/checkout/tournament/{place}', [CheckoutController::class, 'showTournamentCheckout'])->name('checkout.tournament');
    Route::get('/checkout/membership/{id}', [CheckoutController::class, 'showMembershipCheckout'])->name('checkout.membership');
    Route::get('/checkout/tournament/success/{place}', [CheckoutController::class, 'successPlace'])->name('checkout.placeSuccess');
    
    Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
