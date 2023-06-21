<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title_uz' => fake()->title(),
            'title_en' => fake()->title(),
            'title_ru' => fake()->title(),
            'slug' => fake()->slug(),
            'parent_id' => fake()->numberBetween(1, 5),
        ];
    }

}
