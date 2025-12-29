<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomFloat(2, 1, 1000),
            'description' => $this->faker->optional()->sentence(),
            'expense_date' => $this->faker->date(),
            'vendor_id' => \App\Models\Vendor::factory(),
            'expense_category_id' => \App\Models\ExpenseCategory::factory(),
            'is_recurring' => $this->faker->boolean(20),
        ];
    }
}
