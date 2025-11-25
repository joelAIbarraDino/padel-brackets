<?php

namespace App\Http\Controllers;

use App\Models\Places;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
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

    public function storeReschedule(Request $request, Places $place)
    {
        DB::transaction(function () use ($request, $place) {

            // Buscar el torneo nuevo
            $newTournament = Tournament::find($request->tournament);

            if (!$newTournament) {
                throw ValidationException::withMessages([
                    'tournament' => 'No se encontró el torneo seleccionado.'
                ]);
            }

            $newPlace = Places::where('id_tournament', $newTournament->id)
                                ->where('status', 'libre')
                                ->lockForUpdate()
                                ->first();

            if (!$newPlace) {
                throw ValidationException::withMessages([
                    'tournament' => 'No hay lugares disponibles en este torneo.'
                ]);
            }

            // Asignar el nuevo lugar
            $newPlace->id_user = $request->client;
            $newPlace->id_payment = $place->id_payment;
            $newPlace->status = 'ocupado';
            $newPlace->save();

            // Liberar el lugar anterior
            $place->id_user = null;
            $place->id_payment = null;
            $place->status = 'libre';
            $place->save();
        });

        return redirect('/places/' . $place->id_tournament);
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
