<?php

namespace Database\Factories;

use App\Models\MemberWishlist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MemberWishlist>
 */
class MemberWishlistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'memberId' => fake()->numberBetween(1, 10),
            'viewsId' => fake()->numberBetween(1, 25)
        ];
    }
}
