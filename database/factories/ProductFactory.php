<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $brands = ['Havit', 'Logitech', 'Dell', 'HP', 'A4Tech', 'Asus', 'Razer', 'Corsair', 'Redragon', 'SteelSeries'];
        $types = ['Optical Mouse', 'Gaming Mouse', 'Mechanical Keyboard', 'Gaming Keyboard', 'Headset', 'Monitor', 'Webcam', 'Speaker'];

        return [
            'name' => $this->faker->randomElement($brands) . ' ' .
                strtoupper($this->faker->bothify('??-###')) . ' ' .
                $this->faker->randomElement($types),
        ];
    }
}
