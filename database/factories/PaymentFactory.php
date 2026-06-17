<?php

namespace Database\Factories;

use App\Models\CustomOrder;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'custom_order_id' => CustomOrder::factory(),
            'amount' => fake()->numberBetween(100000, 5000000),
            'payment_method' => 'bank_transfer',
            'proof_image_path' => 'payment-proofs/proof.jpg',
            'bank_name' => 'BCA',
            'account_name' => fake()->name(),
            'status' => 'pending',
            'verified_at' => null,
            'admin_notes' => null,
        ];
    }

    /**
     * Indicate that the payment is verified.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'verified',
            'verified_at' => now(),
        ]);
    }

    /**
     * Indicate that the payment is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'admin_notes' => 'Bukti pembayaran tidak valid.',
        ]);
    }
}
