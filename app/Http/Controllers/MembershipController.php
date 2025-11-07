<?php

namespace App\Http\Controllers;

use App\Models\Memberships;
use App\Models\Subscriptions;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Memberships/index', [
            'memberships'=>Memberships::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Memberships/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'description'=>'required|string',
            'months_to_expire'=>'required|numeric|min:0',
            'price'=>'required|numeric|min:0',
        ]);

        Memberships::create($request->all());

        return redirect()->route('memberships.index');
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
    public function edit(Memberships $membership)
    {
        return Inertia::render('Memberships/edit', [
            'membership'=>$membership
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Memberships $membership)
    {
        $request->validate([
            'name'=>'required|string',
            'description'=>'required|string',
            'months_to_expire'=>'required|numeric|min:0',
            'price'=>'required|numeric|min:0',
        ]);

        $membership->update($request->all());

        return redirect()->route('memberships.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Memberships $membership)
    {
        $subs = Subscriptions::where('id_membership', $membership->id);

        if($subs->count() === 0){
            $membership->delete();
        }

        return redirect()->route('memberships.index');
    }
}
