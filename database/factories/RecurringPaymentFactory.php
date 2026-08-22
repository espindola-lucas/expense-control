<?php

namespace Database\Factories;

use App\Enums\MovementType;
use App\Enums\RecurringFrequency;
use App\Models\Account;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RecurringPayment>
 */
class RecurringPaymentFactory extends Factory
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
            'category_id' => Category::factory(),
            'name' => fake()->words(2, true),
            'type' => MovementType::Expense,
            'amount' => fake()->numberBetween(1000, 100000),
            'frequency' => RecurringFrequency::Monthly,
            'start_date' => now()->subMonths(3)->startOfMonth()->toDateString(),
            'end_date' => null,
            'is_active' => true,
            'last_generated_date' => null,
        ];
    }
}
