<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Tournaments/index',[
            'tournaments'=>Tournament::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Tournaments/create');
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
            'status'=>'nullable|string',
            'result'=>'nullable|string'
        ]);

        Tournament::create($request->all());
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
            'tournament' => $tournament
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
            'status'=>'nullable|string',
            'result'=>'nullable|string'
        ]);

        $tournament->update($request->all());
        return redirect()->route('tournaments.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tournament $tournament)
    {
        $tournament->delete();

        return Inertia::render('Tournaments/index', [
            'tournaments'=>Tournament::all(),
        ]);

    }
}
