<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title_uz' => $this->faker->name,
            'title_en' => $this->faker->name,
            'title_ru' => $this->faker->name,
            'description_uz' => $this->faker->text,
            'description_en' => $this->faker->text,
            'description_ru' => $this->faker->text,
            'slug' => $this->faker->slug,
            'images' => json_encode([$this->faker->imageUrl]),
            'page_id' => $this->faker->numberBetween(1,5),
        ];
    }
}
