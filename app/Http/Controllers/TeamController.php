<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        return Team::with(['users', 'projets'])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255|unique:teams,name',
            'description' => 'nullable|string|max:1000',
        ]);

        Team::create($data);
        return back()->with('team_success', 'Équipe ajoutée.');
    }

    public function update(Request $request, Team $team)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255|unique:teams,name,' . $team->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $team->update($data);

        return back()->with('team_success', 'Équipe mise à jour.');
    }

    public function destroy(Team $team)
    {
        User::where('team_id', $team->id)->update(['team_id' => null]);
        $team->delete();
        return back()->with('team_success', 'Équipe supprimée.');
    }
}
