<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $content
 * @property int $user_id
 * @property string $commentable_type
 * @property int $commentable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $commentable
 * @property-read \App\Models\User|null $users
 * @method static \Database\Factories\CommentsFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comments newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comments newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comments query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comments whereCommentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comments whereCommentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comments whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comments whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comments whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comments whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comments whereUserId($value)
 */
	class Comments extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $titre
 * @property string|null $description
 * @property string $state
 * @property int $creator_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comments> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\User $creator
 * @method static \Database\Factories\ImprovementFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Improvement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Improvement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Improvement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Improvement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Improvement whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Improvement whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Improvement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Improvement whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Improvement whereTitre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Improvement whereUpdatedAt($value)
 */
	class Improvement extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $titre
 * @property string|null $description
 * @property int $priority
 * @property string $state
 * @property int $creator_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comments> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\User $creator
 * @method static \Database\Factories\IssueFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issue query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issue whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issue whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issue wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issue whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issue whereTitre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Issue whereUpdatedAt($value)
 */
	class Issue extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nom
 * @property string|null $description
 * @property string|null $date_assignation
 * @property string $avancement
 * @property int $priority
 * @property int|null $team_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comments> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Team|null $team
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\ProjetFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Projet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Projet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Projet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Projet whereAvancement($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Projet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Projet whereDateAssignation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Projet whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Projet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Projet whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Projet wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Projet whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Projet whereUpdatedAt($value)
 */
	class Projet extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Projet> $projets
 * @property-read int|null $projets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\TeamFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Team whereUpdatedAt($value)
 */
	class Team extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $email
 * @property string $mdp
 * @property string $role
 * @property int|null $team_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comments> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Improvement> $improvementsCreated
 * @property-read int|null $improvements_created_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Issue> $issuesCreated
 * @property-read int|null $issues_created_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Projet> $projets
 * @property-read int|null $projets_count
 * @property-read \App\Models\Team|null $team
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereMdp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

