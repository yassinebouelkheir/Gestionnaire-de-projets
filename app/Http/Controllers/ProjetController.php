<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\EntityUpdated;

class ProjetController extends Controller
{   
    public function create()
    {
        $users = \App\Models\User::all();
        $teams = \App\Models\Team::all();
        return view('projets.create', compact('users', 'teams'));
    }

    public function index() {
        $projects = Projet::with(['users', 'team'])
            ->orderBy('created_at', 'asc')
            ->orderBy('priority', 'desc')
            ->get();
        return view('projets.index', compact('projects'));
    }

    public function show($projet)
    {
        $u = Auth::user();

        $issues = $projet->issues()->with('users');
        $imps   = $projet->improvements()->with('users');

        if ($u->role !== 'admin') {
            $issues->where(function($q) use ($u){
                $q->where('creator_id', $u->id)
                ->orWhereHas('users', fn($q2) => $q2->where('users.id', $u->id));
            });

            $imps->where(function($q) use ($u){
                $q->where('creator_id', $u->id)
                ->orWhereHas('users', fn($q2) => $q2->where('users.id', $u->id));
            });
        }

        return view('projets.show', [
            'projet' => $projet,
            'issues' => $issues->get(),
            'improvements' => $imps->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'team_id' => 'nullable|integer',
            'date_assignation' => 'nullable|date',
            'avancement' => 'nullable|integer|min:0|max:100',
            'priority' => 'nullable|integer|in:1,2,3',
        ]);

        $data['creator_id'] = Auth::id();

        $projet = Projet::create($data);

        return redirect()->route('projets.show', $projet);
    }

    public function update(Request $request, Projet $projet)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'team_id' => 'nullable|integer',
            'date_assignation' => 'nullable|date',
            'avancement' => 'nullable|integer|min:0|max:100',
            'priority' => 'nullable|integer|in:1,2,3',
        ]);

        $projet->update($data);

        return redirect()->route('projets.show', $projet);
    }


    public function edit($id)
    {
        $projet = Projet::with('users')->findOrFail($id);
        $users = \App\Models\User::all();
        $teams = \App\Models\Team::all();

        return view('projets.edit', compact('projet', 'users', 'teams'));
    }

    public function destroy(Projet $projet)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $message = "Le projet '{$projet->name}' a été supprimé.";
        foreach ($projet->getUsersToNotify() as $user) {
            $user->notify(new EntityUpdated($projet, 'Projet', $message));
        }

        $projet->delete();
        return redirect()->route('projets.index')->with('success', 'Projet supprimé.');
    }

    public function destroyAll()
    {
        Projet::truncate();
        return response()->json(['message' => 'All projets deleted']);
    }

    public function userProjects()
    {
        $user = auth()->user();
        $isAdmin = $user->role === 'admin';

        if ($isAdmin) {
            $projects = Projet::with([
                'team',
                'users',
                'comments.user',
                'issues.creator', 'issues.users', 'issues.comments.user',
                'improvements.creator', 'improvements.users', 'improvements.comments.user',
            ])
            ->orderBy('created_at', 'asc')
            ->orderBy('priority', 'desc')
            ->get();
        } else {
            $projects = Projet::with(['team', 'users', 'comments.user'])
                ->whereHas('issues', function ($query) use ($user) {
                    $query->where('creator_id', $user->id)
                        ->orWhereHas('users', fn($q) => $q->where('user_id', $user->id));
                })
                ->orWhereHas('improvements', function ($query) use ($user) {
                    $query->where('creator_id', $user->id)
                        ->orWhereHas('users', fn($q) => $q->where('user_id', $user->id));
                })
                ->with(['issues' => function ($query) use ($user) {
                    $query->where('creator_id', $user->id)
                        ->orWhereHas('users', fn($q) => $q->where('user_id', $user->id))
                        ->with(['creator', 'users', 'comments.user']);
                }])
                ->with(['improvements' => function ($query) use ($user) {
                    $query->where('creator_id', $user->id)
                        ->orWhereHas('users', fn($q) => $q->where('user_id', $user->id))
                        ->with(['creator', 'users', 'comments.user']);
                }])
                ->orderBy('created_at', 'asc')
                ->orderBy('priority', 'desc')
                ->get();
        }

        return view('projets.index', compact('projects'));
    }

    public function showProjet($id)
    {
        $projet = Projet::with(['comments', 'issues', 'improvements'])->findOrFail($id);
        return view('projets.show', compact('projet'));
    }
}
