<?php

namespace App\Http\Controllers;

use App\Models\Places;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlacesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Tournament $tournament)
    {
        return Inertia::render('Places/index', [
            'places'=>$tournament->places()->with('user:id,name')->get(),
            'tournamentID' => $tournament->id
        ]);
    }

    public function reschedule(Places $place)
    {
        $tournaments = Tournament::where('status', '=', 'activo')
                        ->where('id', '!=', $place->id_tournament)
                        ->get();

        if ($tournaments->count() === 0) {
            return redirect()->back()->with('error', 'No se puede reagendar porque no existen otros torneos activos.');
        }
        
        return Inertia::render('Places/reschedule', [
            'place' => $place,
            'tournaments' => $tournaments
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
}
