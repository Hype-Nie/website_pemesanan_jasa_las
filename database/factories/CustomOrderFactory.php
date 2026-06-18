<?php

namespace Database\Factories;

use App\Models\CatalogProduct;
use App\Models\CustomOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomOrder>
 */
class CustomOrderFactory extends Factory
{
    protected $model = CustomOrder::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->customer(),
            'catalog_product_id' => CatalogProduct::factory(),
            'product_name' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'dimensions' => '2m x 1.5m',
            'material_preference' => 'Besi Hollow',
            'quantity' => 1,
            'reference_design_path' => 'order-designs/reference.jpg',
            'total_price' => null,
            'status' => 'pending',
            'admin_notes' => null,
        ];
    }

    /**
     * Set the order status.
     */
    public function status(string $status): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => $status,
        ]);
    }
}
