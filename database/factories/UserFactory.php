<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'bio' => $this->faker->paragraph(),
            'image' => $this->faker->url(),
        ];
    }

    public function john()
    {
        return $this->state([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'bio' => 'John Bio',
            'image' => 'https://randomuser.me/api/portraits/men/1.jpg',
        ]);
    }

    public function jane()
    {
        return $this->state([
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'bio' => 'Jane Bio',
            'image' => 'https://randomuser.me/api/portraits/women/1.jpg',
        ]);
    }

    public function bob()
    {
        return $this->state([
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'bio' => 'Bob Bio',
            'image' => 'https://randomuser.me/api/portraits/men/2.jpg',
        ]);
    }

    public function alice()
    {
        return $this->state([
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'bio' => 'Alice Bio',
            'image' => 'https://randomuser.me/api/portraits/women/2.jpg',
        ]);
    }
}
