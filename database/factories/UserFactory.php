<?php

namespace Database\Factories;

use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->firstName() . ' ' . fake()->lastName();

        return [
            'name' => $name,
            'email' => $this->generateUsername($name) . '@' . fake()->safeEmailDomain(),
            'phone' => '+380' . fake()->unique()->randomNumber(9, true),
            'position_id' => Position::inRandomOrder()->first()->id ?? 1,
            'photo' => 'images/users/' . fake()->image('public/storage/images/users', 70, 70, null, false, word: $name, format: 'jpeg')
        ];
    }

    private function generateUsername(string $name): string
    {
        $separators = ['', '.', '_', '-'];

        $uname = strtolower($name);
        $uname = str_replace(' ', $separators[array_rand($separators)], $uname);

        $randomElements = [
            fn() => fake()->word(),
            fn() => fake()->randomNumber(4),
            fn() => fake()->randomLetter() . fake()->randomLetter(),
            fn() => fake()->randomLetter() . fake()->randomLetter() . fake()->randomLetter(),
            fn() => fake()->randomNumber(4) . '-' . fake()->word(),
            fn() => '',
        ];

        $uname .= call_user_func($randomElements[array_rand($randomElements)]);

        // Using to remove all non-alphanumeric characters, except for the separators
        return preg_replace('/[^a-z0-9' . implode('', $separators) . ']/', '', $uname);
    }
}
