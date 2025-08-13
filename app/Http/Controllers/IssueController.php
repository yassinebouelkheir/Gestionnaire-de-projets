<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\EntityUpdated;

class IssueController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $filterUserId = $request->query('user_id');

        if ($user->role === 'admin') {
            if ($filterUserId) {
                $issues = Issue::whereHas('users', function ($q) use ($filterUserId) {
                    $q->where('user_id', $filterUserId);
                })->with(['creator', 'users'])->get();
            } else {
                $issues = Issue::with(['creator', 'users'])->get();
            }
        } elseif ($user->role === 'developer') {
            $issues = $user->assignedIssues()->with(['creator', 'users'])->get();
        } else {
            $issues = $user->issuesCreated()->with(['creator', 'users'])->get();
        }

        return view('issues.index', compact('issues'));
    }

    public function show($id)
    {
        $issue = Issue::with(['creator', 'users', 'comments.user'])->findOrFail($id);
        $user = Auth::user();

        if ($user->role !== 'admin' && $issue->creator_id !== $user->id && !$issue->users->contains($user->id)) {
            abort(403);
        }

        return view('issues.show', compact('issue'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre' => 'required|string',
            'description' => 'required|string',
            'projet_id' => 'required|exists:projets,id',
        ]);

        $issue = new Issue($data);
        $issue->creator_id = Auth::id();
        $issue->save();

        $message = "Un nouveau problème '{$issue->titre}' a été créé.";
        foreach ($issue->getUsersToNotify() as $user) {
            $user->notify(new EntityUpdated($issue, 'Issue', $message));
        }

        return redirect()->route('projets.show', $issue->projet_id);
    }

    public function edit($id)
    {
        $issue = Issue::with('users')->findOrFail($id);
        $user = Auth::user();

        if ($user->role !== 'admin' && !$issue->users->contains($user->id)) {
            abort(403);
        }

        $users = \App\Models\User::all();
        return view('issues.edit', compact('issue', 'users'));
    }

    public function update(Request $request, $id)
    {
        $issue = Issue::findOrFail($id);
        $user = Auth::user();

        $isAdmin = $user->role === 'admin';
        $isAssigned = $issue->users->contains($user->id);
        $isCreator = $issue->creator_id === $user->id;

        if (!$isAdmin && !$isAssigned && !$isCreator) {
            abort(403);
        }

        $data = $request->only(['titre', 'description']);

        if ($isAdmin || $isAssigned) {
            $data += $request->only(['priority', 'state']);
            $issue->users()->sync($request->input('user_ids', []));
        }
        $closing = isset($data['state']) && in_array($data['state'], ['Résolu','Fermé'], true);

        if ($closing && $issue->resolved_at == null) {
            $data['resolved_at'] = now();
        }
        if (!$closing && $issue->resolved_at != null) {
            $data['resolved_at'] = null; 
        }

        $issue->update($data);

        $message = "Le problème '{$issue->titre}' a été modifié.";
        foreach ($issue->getUsersToNotify() as $user) {
            $user->notify(new EntityUpdated($issue, 'Issue', $message));
        }

        return redirect()->route('issues.show', $issue->id)->with('success', 'Issue mise à jour.');
    }

    public function destroy($id)
    {
        $issue = Issue::findOrFail($id);
        $user = Auth::user();

        $isAdmin = $user->role === 'admin';
        $isAssigned = $issue->users->contains($user->id);

        if (!$isAdmin && !$isAssigned) {
            abort(403);
        }

        $message = "Le problème '{$issue->titre}' a été supprimé.";
        foreach ($issue->getUsersToNotify() as $user) {
            $user->notify(new EntityUpdated($issue, 'Issue', $message));
        }

        $issue->delete();

        return redirect()->route('projets.show', $issue->projet_id)->with('success', 'Issue supprimée.');
    }
}
