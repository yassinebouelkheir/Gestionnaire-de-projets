<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index() {
        return Comment::with(['user', 'commentable'])->latest()->get();
    }

    public function show(Comment $comment) {
        return $comment->load(['user', 'commentable']);
    }

    public function store(Request $request) {
        $map = [
            'projet' => \App\Models\Projet::class,
            'issue' => \App\Models\Issue::class,
            'improvement' => \App\Models\Improvement::class,
        ];
        $t = strtolower($request->input('commentable_type', ''));
        if (isset($map[$t])) {
            $request->merge(['commentable_type' => $map[$t]]);
        }

        $data = $request->validate([
            'content' => 'required|string',
            'commentable_type' => 'required|string|in:App\Models\Issue,App\Models\Improvement,App\Models\Projet',
            'commentable_id' => 'required|integer',
        ]);

        $item = $data['commentable_type']::findOrFail($data['commentable_id']);
        $user = Auth::user();

        if (!$this->canAccess($user, $item)) {
            abort(403, 'Pas autorisé');
        }

        $comment = new Comment([
            'content' => $data['content'],
            'user_id' => $user->id,
        ]);
        $item->comments()->save($comment);

        return back();
    }

    public function update(Request $request, Comment $comment) {
        $this->checkOwnerOrAdmin($comment->user_id);
        $request->validate(['content' => 'required|string']);
        $comment->update(['content' => $request->input('content')]);
        return $comment->load(['user','commentable']);
    }

    public function destroy(Comment $comment) {
        $this->checkOwnerOrAdmin($comment->user_id);
        $comment->delete();
        return response()->noContent();
    }

    public function destroyAll() {
        abort_if(Auth::user()->role !== 'admin', 403);
        Comment::truncate();
        return response()->json(['message' => 'Tous les commentaires supprimés']);
    }

    private function checkOwnerOrAdmin(int $ownerId): void
    {
        $u = Auth::user();
        if (!($u->role === 'admin' || $u->id === $ownerId)) {
            abort(403, 'Pas autorisé');
        }
    }

    private function canAccess($u, $item): bool
    {
        if ($u->role === 'admin') return true;
        if (isset($item->creator_id) && $item->creator_id === $u->id) return true;
        if (method_exists($item, 'users') && $item->users()->where('users.id', $u->id)->exists()) return true;
        if (method_exists($item, 'projet') && $item->projet && $item->projet->users()->where('users.id', $u->id)->exists()) return true;
        if ($item instanceof \App\Models\Projet && $item->users()->where('users.id', $u->id)->exists()) return true;
        return false;
    }
}
