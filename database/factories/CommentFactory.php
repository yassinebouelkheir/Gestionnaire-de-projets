<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = \App\Models\Comment::class;

    public function definition()
    {
        $commentable = \App\Models\Projet::inRandomOrder()->first();

        return [
            'content' => $this->faker->sentence(10),
            'user_id' => \App\Models\User::factory(),
            'commentable_id' => $commentable->id,
            'commentable_type' => get_class($commentable),
        ];
    }
}
