<?php

namespace App\Http\Controllers;

use App\Models\Places;
use App\Models\Tournament;
use App\Models\Type_tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        return Inertia::render('Tournaments/index',[
            'tournaments'=>Tournament::with(['type_tournament:id,name'])->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Tournaments/create', [
            'typeTournaments'=>Type_tournament::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type'=>'required|numeric',
            'scheduled_event'=>'required|string',
            'admission_price'=>'required|numeric|min:0',
            'places' => [
                'required',
                'numeric',
                'min:4',
                'max:20',
                function($attribute,$value, $fail) {
                    if ($value % 4 !== 0) {
                        $fail('El número de lugares debe ser divisible entre 4.');
                    }
                }
            ],
            'status'=>'nullable|string',
            'result'=>'nullable|string'
        ]);

        //se inicia una transiccion para crear el registro del torneo y los lugares disponibles
        DB::transaction( function () use ($request){
            $newTournament = Tournament::create($request->all());
        
            for($i = 0; $i < $newTournament->places; $i++){
                Places::create([
                    'id_user'=>null,
                    'id_tournament'=>$newTournament->id,
                    'id_payment'=>null,
                    'status'=>'libre'
                ]);
            }
        });
        
        return redirect()->route('tournaments.index');
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
    public function edit(Tournament $tournament)
    {
        return Inertia::render('Tournaments/edit', [
            'tournament' => $tournament,
            'typeTournaments'=>Type_tournament::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tournament $tournament)
    {
        $request->validate([
            'type'=>'required|numeric',
            'scheduled_event'=>'required|string',
            'admission_price'=>'required|numeric|min:0',
            'places' => [
                'required',
                'numeric',
                'min:4',
                'max:20',
                function($attribute, $value, $fail) {
                    if ($value % 4 !== 0) {
                        $fail('El número de lugares debe ser divisible entre 4.');
                    }
                }
            ],
            'status'=>'nullable|string',
            'result'=>'nullable|string'
        ]);

        DB::transaction(function() use ($request, $tournament) {
            $oldPlaces = $tournament->places;
            $newPlaces = $request->places;

            $tournament->update($request->all());
            
            if ($newPlaces > $oldPlaces) {
                $toAdd = $newPlaces - $oldPlaces;
                for ($i = 0; $i < $toAdd; $i++) {
                    Places::create([
                        'id_user' => null,
                        'id_tournament' => $tournament->id,
                        'id_payment' => null,
                        'status' => 'libre'
                    ]);
                }
            } elseif ($newPlaces < $oldPlaces) {
                $toRemove = $oldPlaces - $newPlaces;
                $placesToDelete = Places::where('id_tournament', $tournament->id)
                    ->whereNull('id_user')
                    ->whereNull('id_payment')
                    ->where('status', 'libre')
                    ->orderByDesc('id')
                    ->take($toRemove)
                    ->get();

                if ($placesToDelete->count() < $toRemove) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'places' => 'No se pueden eliminar lugares porque todos están ocupados.'
                    ]);
                }

                foreach ($placesToDelete as $place) {
                    $place->delete();
                }
            }
        });
        
        return redirect()->route('tournaments.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tournament $tournament)
    {
        DB::transaction(function() use ($tournament){
            
            $places = Places::where('id_tournament', $tournament->id)->get();

            $takenPlaces = $places->filter(function($place){
                return $place->status === 'ocupado';
            }); 
            
            if($takenPlaces->count() == 0){

                foreach($places as $place){
                    $place->delete();
                }

                $tournament->delete();
            }
            
        });

        return Inertia::render('Tournaments/index', [
            'tournaments'=>Tournament::all(),
        ]);

    }
}
