<?php

namespace App\Http\Controllers;

use App\Models\Improvement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\EntityUpdated;

class ImprovementController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $filterUserId = $request->query('user_id');

        if ($user->role === 'admin') {
            if ($filterUserId) {
                $improvements = Improvement::whereHas('users', function ($q) use ($filterUserId) {
                    $q->where('user_id', $filterUserId);
                })->with(['creator', 'users'])->get();
            } else {
                $improvements = Improvement::with(['creator', 'users'])->get();
            }
        } elseif ($user->role === 'developer') {
            $improvements = $user->assignedImprovements()->with(['creator', 'users'])->get();
        } else {
            $improvements = $user->improvementsCreated()->with(['creator', 'users'])->get();
        }

        return view('improvements.index', compact('improvements'));
    }

    public function show($id)
    {
        $improvement = Improvement::with(['creator', 'users', 'comments.user'])->findOrFail($id);
        $user = Auth::user();

        if ($user->role !== 'admin' && $improvement->creator_id !== $user->id && !$improvement->users->contains($user->id)) {
            abort(403);
        }

        return view('improvements.show', compact('improvement'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre' => 'required|string',
            'description' => 'required|string',
            'projet_id' => 'required|exists:projets,id',
        ]);

        $improvement = new Improvement($data);
        $improvement->creator_id = Auth::id();
        $improvement->save();

        $message = "Une nouvelle amélioration '{$improvement->titre}' a été créée.";
        foreach ($improvement->getUsersToNotify() as $user) {
            $user->notify(new EntityUpdated($improvement, 'Improvement', $message));
        }

        return redirect()->route('projets.show', $improvement->projet_id);
    }

    public function edit($id)
    {
        $improvement = Improvement::with('users')->findOrFail($id);
        $user = Auth::user();

        if ($user->role !== 'admin' && !$improvement->users->contains($user->id)) {
            abort(403);
        }

        $users = \App\Models\User::all();
        return view('improvements.edit', compact('improvement', 'users'));
    }

    public function update(Request $request, $id)
    {
        $improvement = Improvement::findOrFail($id);
        $user = Auth::user();

        $isAdmin = $user->role === 'admin';
        $isAssigned = $improvement->users->contains($user->id);
        $isCreator = $improvement->creator_id === $user->id;

        if (!$isAdmin && !$isAssigned && !$isCreator) {
            abort(403);
        }

        $request->validate([
            'titre' => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
            'state' => 'sometimes|string|in:Ouvert,En cours,Résolu,Fermé',
            'user_ids' => 'sometimes|array',
            'user_ids.*' => 'integer|exists:users,id',
        ]);

        $data = $request->only(['titre', 'description']);

        if ($isAdmin || $isAssigned) {
            $data += $request->only(['state']);
            $improvement->users()->sync($request->input('user_ids', []));
        }

        $improvement->update($data);

        $message = "L'amélioration '{$improvement->titre}' a été modifiée.";
        foreach ($improvement->getUsersToNotify() as $user) {
            $user->notify(new EntityUpdated($improvement, 'Improvement', $message));
        }

        return redirect()->route('improvements.show', $improvement->id)->with('success', 'Amélioration mise à jour.');
    }

    public function destroy($id)
    {
        $improvement = Improvement::findOrFail($id);
        $user = Auth::user();

        $isAdmin = $user->role === 'admin';
        $isAssigned = $improvement->users->contains($user->id);

        if (!$isAdmin && !$isAssigned) {
            abort(403);
        }

        $message = "L'amélioration '{$improvement->titre}' a été supprimée.";
        foreach ($improvement->getUsersToNotify() as $user) {
            $user->notify(new EntityUpdated($improvement, 'Improvement', $message));
        }

        $improvement->delete();

        return redirect()->route('projets.show', $improvement->projet_id)->with('success', 'Amélioration supprimée.');
    }
}
