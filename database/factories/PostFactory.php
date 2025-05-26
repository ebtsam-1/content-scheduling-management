<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['draft', 'published', 'scheduled'];
        $user = User::factory()->create();
        return [
            'title' => fake()->word,
            'content' => fake()->paragraph(),
            'image_url' => 'https://picsum.photos/seed/picsum/200/300',
            'status' => 'draft',
            'user_id' => $user->id,
        ];
    }
}
