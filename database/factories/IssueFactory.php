<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class IssueFactory extends Factory
{
    protected $model = \App\Models\Issue::class;

    public function definition()
    {
        $state = $this->faker->randomElement(['Ouvert', 'En cours', 'Résolu', 'Fermé']);

        $createdAt = $this->faker->dateTimeBetween('-1 month', 'now');
        $resolvedAt = null;

        if (in_array($state, ['Résolu', 'Fermé'])) {
            $resolvedAt = $this->faker->dateTimeBetween($createdAt, 'now');
        }

        return [
            'titre' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'priority' => $this->faker->numberBetween(1, 3),
            'state' => $state,
            'creator_id' => \App\Models\User::factory(),
            'created_at' => $createdAt,
            'updated_at' => $this->faker->dateTimeBetween($createdAt, 'now'),
            'resolved_at' => $resolvedAt ? Carbon::instance($resolvedAt) : null,
        ];
    }
}
