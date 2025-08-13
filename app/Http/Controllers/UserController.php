<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('team')->get();
        $teams = Team::withCount('users')->orderBy('name')->get();

        return view('users.index', compact('users', 'teams'));
    }

    public function edit(User $user)
    {
        $roles = ['admin', 'developer', 'user'];
        $teams = Team::orderBy('name')->get();
        $user->load('team');

        return view('users.edit', compact('user', 'roles', 'teams'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role'    => 'required|in:admin,developer,user',
            'team_id' => 'nullable|exists:teams,id',
        ]);

        $user->role    = $request->input('role');
        $user->team_id = $request->input('team_id'); 
        $user->save();

        return redirect()->route('users.index')->with('success', 'Rôle et équipe mis à jour.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé.');
    }
}
