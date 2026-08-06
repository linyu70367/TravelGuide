<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'memberName' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'pwd' => bcrypt('password'),
            'tel' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'birthday' => fake()->date(),
            'status' => $this->faker->randomElement(["正常", "未驗證", "停權"]),
            'avatar' => 'default_avatar.png'
        ];
    }
}
