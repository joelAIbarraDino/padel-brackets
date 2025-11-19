<?php

use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

//rutas publicas
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/torneos', [PublicController::class, 'tournaments'])->name('tournaments');
Route::get('/torneos/places/{tournament}', [PublicController::class, 'places'])->name('placesTournaments');

Route::get('/checkout/tournament/{place}', [CheckoutController::class, 'showTournamentCheckout'])->name('checkout.tournament');
Route::get('/checkout/membership/{membership}', [CheckoutController::class, 'showMembershipCheckout'])->name('checkout.membership');
Route::get('/checkout/tournament/success/{place}', [CheckoutController::class, 'successPlace'])->name('checkout.placeSuccess');
Route::get('/checkout/membership/success/{membership}', [CheckoutController::class, 'successMembership'])->name('checkout.membershipSuccess');
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

require __DIR__.'/admin.php';
require __DIR__.'/player.php';
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
