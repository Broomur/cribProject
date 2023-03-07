<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Album;
use Inertia\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Team/Index', [
            'team' => Team::simplePaginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Team/Create', [
            'users' => User::where('type', 'pro')
                            ->where('active', true)
                            ->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        {
            $validated = $request->validate([
                'name' => 'string|max:55|required',
                'picture' => 'image|required',
            ]);
    
            $user = User::where('name', $validated['name'])->first();
                       
            $name = $validated['picture']->hashName();
            $store = Storage::disk('team')->put('/', $validated['picture']);
            $path = Storage::disk('team')->url($name);
            $picture = Team::create([
                'name' => $validated['name'],
                'path' => $path,
                'user_id' => $user->id,
            ]);
    
            return Redirect::route('team.index')->with('message', 'La photo a été importé avec succès !');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'picture' => 'image|required',
        ]);

        $name = explode('/', $team->path)[5];
        $deleteFile = Storage::disk('team')->delete($name);

        $name = $request['picture']->hashName();
        $storeFile = Storage::disk('team')->put('/', $request['picture']);
        $path = Storage::disk('team')->url($name);
        
        $updateTeam = $team->update([
            'path' => $path, 
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        //
    }
}
