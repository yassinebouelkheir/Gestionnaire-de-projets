<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Notifications\DatabaseNotification;

class NotificationFactory extends Factory
{
    protected $model = DatabaseNotification::class;

    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'type' => 'App\Notifications\EntityUpdated',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => 1,
            'data' => [
                'entity_type' => 'Projet',
                'message' => $this->faker->sentence(),
                'entity_id' => 1,
            ],
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
