<?php

namespace Tests\Feature\Blackbox;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CustomerPaymentTest extends BlackboxTestCase
{
    public function test_customer_can_upload_payment_proof_for_own_non_cancelled_order(): void
    {
        $this->fakePublicDisk();
        $customer = $this->customer();
        $order = $this->order($customer, [
            'status' => 'confirmed',
            'total_price' => 1250000,
        ]);

        $response = $this->actingAs($customer)
            ->post(route('customer.payments.store', $order->id), [
                'amount' => 1250000,
                'proof_image' => UploadedFile::fake()->create('bukti.jpg', 64, 'image/jpeg'),
                'bank_name' => 'BCA',
                'account_name' => 'Andi Customer',
                'payment_method' => 'bank_transfer',
            ]);

        $payment = $order->payments()->first();

        $response->assertRedirect(route('customer.orders.show', $order->id));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('payments', [
            'custom_order_id' => $order->id,
            'amount' => 1250000,
            'bank_name' => 'BCA',
            'account_name' => 'Andi Customer',
            'status' => 'pending',
        ]);
        Storage::disk('public')->assertExists($payment->proof_image_path);
    }

    public function test_payment_upload_validation_rejects_invalid_amount_and_file(): void
    {
        $this->fakePublicDisk();
        $customer = $this->customer();
        $order = $this->order($customer, ['status' => 'confirmed']);

        $response = $this->actingAs($customer)
            ->from(route('customer.payments.create', $order->id))
            ->post(route('customer.payments.store', $order->id), [
                'amount' => 0,
                'proof_image' => UploadedFile::fake()->create('bukti.pdf', 64, 'application/pdf'),
            ]);

        $response->assertRedirect(route('customer.payments.create', $order->id));
        $response->assertSessionHasErrors(['amount', 'proof_image']);
    }

    public function test_customer_cannot_pay_cancelled_or_other_users_order(): void
    {
        $customer = $this->customer();
        $cancelled = $this->order($customer, ['status' => 'cancelled']);
        $otherOrder = $this->order($this->customer(), ['status' => 'confirmed']);

        $this->actingAs($customer)
            ->get(route('customer.payments.create', $cancelled->id))
            ->assertNotFound();

        $this->actingAs($customer)
            ->get(route('customer.payments.create', $otherOrder->id))
            ->assertNotFound();
    }
}
