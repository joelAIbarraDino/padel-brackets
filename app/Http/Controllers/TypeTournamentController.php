<?php

namespace App\Http\Controllers;

use App\Models\Type_tournament;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TypeTournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('TypeTournaments/index', [
            'typeTournaments'=>Type_tournament::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('TypeTournaments/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string'
        ]);

        Type_tournament::create($request->all());
        return redirect()->route('typeTournaments.index');
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
    public function edit(Type_tournament $typeTournament)
    {
        return Inertia::render('TypeTournaments/edit', [
            'typeTournament'=> $typeTournament
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Type_tournament $typeTournament)
    {
        $request->validate([
            'name'=>'required|string'
        ]);

        $typeTournament->update($request->all());
        return redirect()->route('typeTournaments.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Type_tournament $typeTournament)
    {
        $typeTournament->delete();

        return Inertia::render('Tournaments/index', [
            'typeTournaments'=>Type_tournament::all(),
        ]);
    }
}
