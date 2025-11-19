<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PlayerController extends Controller
{
    public function dashboard(Request $request){

        $user = $request->user();

        // Torneos del usuario a través de places
        $tournaments = $user->tournaments()
            ->with(['typeTournament'])
            ->get();

        // Membresía activa (si existe)
        $subscription = $user->activeSubscription()
            ->with('membership')
            ->first();

        return Inertia::render('Client', [
            'tournaments' => $tournaments,
            'subscription' => $subscription,
        ]);
    }
}
