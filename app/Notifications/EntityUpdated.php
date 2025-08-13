<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EntityUpdated extends Notification
{
    use Queueable;

    protected $entity;
    protected $entityType;
    protected $message;

    public function __construct($entity, string $entityType, string $message)
    {
        $this->entity = $entity;
        $this->entityType = $entityType;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $url = route(strtolower($this->entityType).'s.show', $this->entity->id);

        return (new MailMessage)
            ->subject("Mise à jour sur {$this->entityType}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line($this->message)
            ->action("Voir le {$this->entityType}", $url)
            ->line('Merci d’utiliser notre application.');
    }

    public function toDatabase($notifiable)
    {
        if ($this->entity instanceof \App\Models\Comment) {
            $parent = $this->entity->commentable; 
            $url = $this->urlForParent($parent, $this->entity->id);

            return [
                'entity_id'   => $parent->id,
                'entity_type' => class_basename($parent),
                'message'     => $this->message,
                'url'         => $url,
            ];
        }

        $url = $this->urlForParent($this->entity);
        return [
            'entity_id'   => $this->entity->id,
            'entity_type' => $this->entityType,       
            'message'     => $this->message,
            'url'         => $url,
        ];
    }

    private function urlForParent($model, ?int $commentId = null): string
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
            if ($commentId) $url .= '#comment-' . $commentId;
            return $url;
        }

        return url('/');
    }

}
