<?php

namespace App\Http\Controllers;

use App\Models\Memberships;
use App\Models\Tournament;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PublicController extends Controller
{
    public function home()
    {
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

        $memberships = Memberships::all();

        return Inertia::render('Home', [
            'events'=>$tournaments,
            'memberships'=>$memberships
        ]);
    }

    public function tournaments()
    {
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
    }

    public function places(Tournament $tournament){
        return Inertia::render('public/places', [
            'places'=>$tournament->places()->with('user:id,name')->get(),
            'tournament'=>$tournament
        ]);
    }
}
