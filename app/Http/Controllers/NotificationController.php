<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markRead(string $id)
    {
        $n = Auth::user()->notifications()->findOrFail($id);
        $n->markAsRead();

        $data = $n->data ?? [];

        if (!empty($data['url'])) {
            return redirect($data['url']);
        }

        if (($data['entity_type'] ?? '') === 'Comment' || ($data['entity_type'] ?? '') === \App\Models\Comment::class) {
            $comment = \App\Models\Comment::with('commentable')->find($data['entity_id'] ?? null);
            if ($comment?->commentable) {
                return redirect($this->parentUrl($comment->commentable, $comment->id));
            }
        }

        if (!empty($data['entity_type']) && !empty($data['entity_id'])) {
            $type = strtolower(class_basename($data['entity_type']));
            $routes = [
                'improvement' => 'improvements.show',
                'issue'       => 'issues.show',
                'projet'      => 'projets.show',
            ];
            if (isset($routes[$type])) {
                return redirect()->route($routes[$type], $data['entity_id']);
            }
        }

        return redirect('/');
    }

    private function parentUrl($model, ?int $commentId = null): string
    {
        $type = strtolower(class_basename($model));
        $routes = [
            'improvement' => 'improvements.show',
            'issue'       => 'issues.show',
            'projet'      => 'projets.show',
        ];

        $name = $routes[$type] ?? null;
        if ($name) {
            $url = route($name, $model->id);
            if ($commentId) {
                $url .= "#comment-$commentId"; 
            }
            return $url;
        }

        return url('/');
    }
    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back();
    }
}
