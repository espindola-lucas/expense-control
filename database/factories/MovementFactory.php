<?php

namespace Database\Factories;

use App\Enums\MovementType;
use App\Models\Account;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movement>
 */
class MovementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'account_id' => Account::factory(),
            'destination_account_id' => null,
            'category_id' => Category::factory(),
            'type' => MovementType::Expense,
            'name' => fake()->words(3, true),
            'amount' => fake()->numberBetween(100, 100000),
            'movement_date' => fake()->date(),
        ];
    }
}
