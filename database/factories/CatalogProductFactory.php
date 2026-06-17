<?php

namespace Database\Factories;

use App\Models\CatalogProduct;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CatalogProduct>
 */
class CatalogProductFactory extends Factory
{
    protected $model = CatalogProduct::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'category_id' => Category::factory(),
            'material' => fake()->randomElement(['Besi Hollow', 'Baja Ringan', 'Stainless Steel']),
            'price_estimate' => fake()->numberBetween(250000, 5000000),
            'image_path' => null,
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the catalog product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
