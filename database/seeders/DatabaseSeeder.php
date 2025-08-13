<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Team;
use App\Models\Projet;
use App\Models\Issue;
use App\Models\Improvement;
use App\Models\Comment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $teams = [];
        for ($t = 1; $t <= 3; $t++) {
            $teams[$t] = Team::create(['name' => "Équipe $t"]);
        }

        $admins = [];
        for ($i = 1; $i <= 3; $i++) {
            $admins[$i] = User::create([
                'name' => "Admin $i",
                'email' => "admin$i@example.com",
                'password' => Hash::make('password'),
                'role' => 'admin',
                'team_id' => $teams[array_rand($teams)]->id,
            ]);
        }

        $devs = [];
        for ($i = 1; $i <= 10; $i++) {
            $devs[$i] = User::create([
                'name' => "Dev $i",
                'email' => "dev$i@example.com",
                'password' => Hash::make('password'),
                'role' => 'developer',
                'team_id' => $teams[array_rand($teams)]->id,
            ]);
        }

        $users = [];
        for ($i = 1; $i <= 20; $i++) {
            $users[$i] = User::create([
                'name' => "User $i",
                'email' => "user$i@example.com",
                'password' => Hash::make('password'),
                'role' => 'user',
                'team_id' => $teams[array_rand($teams)]->id,
            ]);
        }

        $projets = [];
        for ($p = 1; $p <= 30; $p++) {
            $team = $teams[array_rand($teams)];
            $projets[$p] = Projet::create([
                'name' => "Projet $p",
                'description' => "Description du projet $p",
                'date_assignation' => now(),
                'avancement' => rand(0, 100),
                'priority' => rand(1, 3),
                'team_id' => $team->id,
            ]);

            $membersToAttach = array_merge(
                array_rand($admins, 1) ? [$admins[array_rand($admins)]->id] : [],
                array_rand($devs, 3) ? array_map(fn($k) => $devs[$k]->id, array_rand($devs, 3)) : [],
                array_rand($users, 5) ? array_map(fn($k) => $users[$k]->id, array_rand($users, 5)) : []
            );
            $projets[$p]->users()->sync(array_unique($membersToAttach));
        }

        for ($j = 1; $j <= 100; $j++) {
            $projet = $projets[array_rand($projets)];
            $creatorCandidates = $projet->users()->pluck('user_id')->toArray();
            $creator_id = $creatorCandidates[array_rand($creatorCandidates)] ?? $admins[1]->id;

            $states = ['Ouvert', 'En cours', 'Résolu', 'Fermé'];
            $state = $states[array_rand($states)];
            $priority = rand(1, 3);

            $createdAt = Carbon::now()
                ->subDays(rand(0, 90))
                ->subHours(rand(0, 23))
                ->subMinutes(rand(0, 59));

            $issue = Issue::create([
                'titre' => "Problème $j",
                'description' => "Détail du problème $j",
                'priority' => $priority,
                'state' => $state,
                'creator_id' => $creator_id,
                'projet_id' => $projet->id,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            if (in_array($state, ['Résolu', 'Fermé'], true)) {
                $ranges = [
                    'Résolu' => [
                        1 => ['min' => 5,  'max' => 45],
                        2 => ['min' => 2,  'max' => 30],
                        3 => ['min' => 0,  'max' => 14],
                    ],
                    'Fermé' => [
                        1 => ['min' => 10, 'max' => 90],
                        2 => ['min' => 5,  'max' => 60],
                        3 => ['min' => 2,  'max' => 30],
                    ],
                ];
                $r = $ranges[$state][$priority];
                $resolvedAt = (clone $createdAt)
                    ->addDays(rand($r['min'], $r['max']))
                    ->addHours(rand(0, 23))
                    ->addMinutes(rand(0, 59));
                if ($resolvedAt->gt(Carbon::now())) {
                    $resolvedAt = Carbon::now()->subMinutes(rand(0, 120));
                    if ($resolvedAt->lt($createdAt)) {
                        $resolvedAt = (clone $createdAt)->addMinutes(rand(5, 720));
                    }
                }
                $issue->resolved_at = $resolvedAt;
                $issue->updated_at = $resolvedAt;
                $issue->save();
            }

            $possibleDevs = $projet->users()->where('role', 'developer')->pluck('user_id')->toArray();
            if (!empty($possibleDevs)) {
                $issue->users()->sync([$possibleDevs[array_rand($possibleDevs)]]);
            }

            $commenters = $projet->users()->pluck('user_id')->toArray();
            for ($k = 1; $k <= rand(1, 3); $k++) {
                Comment::create([
                    'content' => "Commentaire $k sur le problème $j",
                    'user_id' => $commenters[array_rand($commenters)] ?? $creator_id,
                    'commentable_type' => Issue::class,
                    'commentable_id' => $issue->id,
                ]);
            }
        }

        for ($j = 1; $j <= 100; $j++) {
            $projet = $projets[array_rand($projets)];
            $creatorCandidates = $projet->users()->pluck('user_id')->toArray();
            $creator_id = $creatorCandidates[array_rand($creatorCandidates)] ?? $admins[1]->id;

            $states = ['Ouvert', 'En cours', 'Résolu', 'Fermé'];
            $state = $states[array_rand($states)];

            $improvement = Improvement::create([
                'titre' => "Amélioration $j",
                'description' => "Détail de l'amélioration $j",
                'state' => $state,
                'creator_id' => $creator_id,
                'projet_id' => $projet->id,
            ]);

            $possibleDevs = $projet->users()->where('role', 'developer')->pluck('user_id')->toArray();
            if (!empty($possibleDevs)) {
                $improvement->users()->sync([$possibleDevs[array_rand($possibleDevs)]]);
            }

            $commenters = $projet->users()->pluck('user_id')->toArray();
            for ($k = 1; $k <= rand(1, 3); $k++) {
                Comment::create([
                    'content' => "Commentaire $k sur l'amélioration $j",
                    'user_id' => $commenters[array_rand($commenters)] ?? $creator_id,
                    'commentable_type' => Improvement::class,
                    'commentable_id' => $improvement->id,
                ]);
            }
        }

        foreach ($projets as $index => $projet) {
            $members = $projet->users()->pluck('user_id')->toArray();
            for ($k = 1; $k <= rand(1, 3); $k++) {
                Comment::create([
                    'content' => "Commentaire $k sur le projet {$projet->id}",
                    'user_id' => $members[array_rand($members)] ?? $admins[1]->id,
                    'commentable_type' => Projet::class,
                    'commentable_id' => $projet->id,
                ]);
            }
        }
        
        $usersList = User::all();
        $entities = ['Projet', 'Issue', 'Improvement', 'Comment'];

        for ($n = 1; $n <= 500; $n++) {
            $user = $usersList->random();
            $entityType = $entities[array_rand($entities)];
            $entityId = null;
            $message = '';

            switch ($entityType) {
                case 'Projet':
                    $entity = $projets[array_rand($projets)];
                    $entityId = $entity->id;
                    $message = "Notification sur le projet '{$entity->name}'";
                    break;

                case 'Issue':
                    $issue = Issue::inRandomOrder()->first();
                    if ($issue) {
                        $entityId = $issue->id;
                        $message = "Notification sur le problème '{$issue->titre}'";
                    }
                    break;

                case 'Improvement':
                    $improvement = Improvement::inRandomOrder()->first();
                    if ($improvement) {
                        $entityId = $improvement->id;
                        $message = "Notification sur l'amélioration '{$improvement->titre}'";
                    }
                    break;

                case 'Comment':
                    $comment = Comment::inRandomOrder()->first();
                    if ($comment) {
                        $entityId = $comment->id;
                        $message = "Notification sur un commentaire";
                    }
                    break;
            }

            if ($entityId) {
                DB::table('notifications')->insert([
                    'id' => (string) Str::uuid(),
                    'type' => 'App\\Notifications\\EntityUpdated',
                    'notifiable_type' => User::class,
                    'notifiable_id' => $user->id,
                    'data' => json_encode([
                        'entity_type' => $entityType,
                        'message' => $message,
                        'entity_id' => $entityId,
                    ]),
                    'read_at' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
