<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(), // Supplier/company name
            'mobile' => $this->faker->phoneNumber(), // Mobile number
            'email' => $this->faker->unique()->safeEmail(), // Unique email
            'address' => $this->faker->address(), // Full address
            'status' => $this->faker->randomElement(['active', 'inactive']), // Status
        ];
    }
}
