<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\NotifiableTrait;

class Improvement extends Model
{
    use HasFactory, NotifiableTrait;

    protected $fillable = ['titre', 'description', 'projet_id', 'state', 'creator_id'];


    public const STATE_OPEN    = 'Ouvert';
    public const STATE_PENDING = 'En cours';
    public const STATE_RESOLVED= 'Résolu';
    public const STATE_CLOSED  = 'Fermé';

    public static function allowedStates(): array
    {
        return [self::STATE_OPEN, self::STATE_PENDING, self::STATE_RESOLVED, self::STATE_CLOSED];
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }
    public function attachments()
    {
        return $this->morphMany(\App\Models\Attachment::class, 'attachable');
    }
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}

