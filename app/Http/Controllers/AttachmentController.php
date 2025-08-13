<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attachment;

class AttachmentController extends Controller
{
    public function store(Request $request)
    {
        $rawType = (string) $request->input('attachable_type', '');
        $type = ltrim($rawType, '\\');
        $map = [
            'projet'       => \App\Models\Projet::class,
            'project'      => \App\Models\Projet::class,
            'issue'        => \App\Models\Issue::class,
            'improvement'  => \App\Models\Improvement::class,
        ];
        $base = strtolower(class_basename($type));
        if (isset($map[$base])) {
            $type = $map[$base];
            $request->merge(['attachable_type' => $type]);
        }

        $data = $request->validate([
            'attachment' => 'required|file|max:10240',
            'attachable_type' => 'required|string|in:App\Models\Issue,App\Models\Improvement,App\Models\Projet',
            'attachable_id' => 'required|integer',
        ]);

        $attachable = $data['attachable_type']::findOrFail($data['attachable_id']);
        $user = \Illuminate\Support\Facades\Auth::user();

        if (!$this->canInteract($user, $attachable)) {
            abort(403, 'Action non autorisée.');
        }

        $file = $request->file('attachment');
        $path = $file->store('attachments', 'public');

        Attachment::create([
            'filename' => $file->getClientOriginalName(),
            'path' => $path,
            'attachable_type' => $data['attachable_type'],
            'attachable_id' => $data['attachable_id'],
        ]);
        return back();
    }

    private function canInteract($user, $entity): bool
    {
        if ($user->role === 'admin') return true;

        if (isset($entity->creator_id) && $entity->creator_id === $user->id) return true;

        if (method_exists($entity, 'users') && $entity->users()->where('users.id', $user->id)->exists()) return true;

        if (method_exists($entity, 'projet') && $entity->projet && $entity->projet->users()->where('users.id', $user->id)->exists()) return true;
        if ($entity instanceof \App\Models\Projet && $entity->users()->where('users.id', $user->id)->exists()) return true;

        return false;
    }
}
