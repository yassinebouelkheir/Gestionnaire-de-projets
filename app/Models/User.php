<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


/**
 * @property int $id
 * @property string $email
 * @property string $mdp
 * @property string $role
 * @property int|null $team_id
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'email', 'password', 'role', 'team_id'];

    protected $hidden = ['password', 'remember_token'];

    public function projets() 
    {
        return $this->belongsToMany(Projet::class);
    }

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    public function issuesCreated()
    {
        return $this->hasMany(Issue::class, 'creator_id');
    }

    public function assignedIssues()
    {
        return $this->belongsToMany(Issue::class);
    }

    public function improvementsCreated()
    {
        return $this->hasMany(Improvement::class, 'creator_id');
    }

    public function assignedImprovements()
    {
        return $this->belongsToMany(Improvement::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    public function issues()
    {
        return $this->belongsToMany(\App\Models\Issue::class, 'issue_user', 'user_id', 'issue_id');
    }
}
