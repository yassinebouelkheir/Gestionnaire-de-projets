<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ImprovementFactory extends Factory
{
    protected $model = \App\Models\Improvement::class;

    public function definition()
    {
        return [
            'titre' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'state' => $this->faker->randomElement(['Ouvert', 'En cours', 'Résolu', 'Fermé']),
            'creator_id' => \App\Models\User::factory(),
        ];
    }
}
