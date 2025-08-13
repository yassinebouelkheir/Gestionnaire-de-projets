<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjetFactory extends Factory
{
    protected $model = \App\Models\Projet::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'date_assignation' => $this->faker->date(),
            'avancement' => $this->faker->numberBetween(20, 80),
            'priority' => $this->faker->numberBetween(1, 3),
            'team_id' => null, // assign in seeder if needed
        ];
    }
}
