<?php

namespace Tests\Feature\Blackbox;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CustomerOrderTest extends BlackboxTestCase
{
    public function test_customer_order_index_only_shows_authenticated_users_orders_and_status_filter(): void
    {
        $customer = $this->customer();
        $otherCustomer = $this->customer();
        $pendingOrder = $this->order($customer, ['product_name' => 'Pagar Saya', 'status' => 'pending']);
        $completedOrder = $this->order($customer, ['product_name' => 'Kanopi Selesai', 'status' => 'completed']);
        $this->order($otherCustomer, ['product_name' => 'Order Orang Lain', 'status' => 'pending']);

        $response = $this->actingAs($customer)->get(route('customer.orders.index'));

        $response->assertOk();
        $response->assertSeeText($pendingOrder->order_code);
        $response->assertSeeText($completedOrder->order_code);
        $response->assertDontSeeText('Order Orang Lain');

        $filtered = $this->actingAs($customer)
            ->get(route('customer.orders.index', ['status' => 'completed']));

        $filtered->assertOk();
        $filtered->assertSeeText('Kanopi Selesai');
        $filtered->assertDontSeeText('Pagar Saya');
    }

    public function test_order_create_route_with_catalog_product_prefills_product_information(): void
    {
        $customer = $this->customer();
        $product = $this->product([
            'name' => 'Pagar Prefill',
            'description' => 'Deskripsi produk prefill',
        ]);

        $response = $this->actingAs($customer)
            ->get(route('customer.orders.create', $product->id));

        $response->assertOk();
        $response->assertSee('value="Pagar Prefill"', false);
        $response->assertSee('name="catalog_product_id"', false);
        $response->assertSee((string) $product->id, false);
    }

    public function test_customer_can_create_order_with_reference_design_upload(): void
    {
        $this->fakePublicDisk();
        $customer = $this->customer();
        $product = $this->product(['name' => 'Produk Katalog']);

        $response = $this->actingAs($customer)->post(route('customer.orders.store'), [
            'product_name' => 'Pagar Custom',
            'description' => 'Pagar depan rumah dengan motif minimalis.',
            'dimensions' => '3m x 1.5m',
            'material_preference' => 'Besi Hollow',
            'quantity' => 2,
            'catalog_product_id' => $product->id,
            'reference_design' => UploadedFile::fake()->create('referensi.jpg', 64, 'image/jpeg'),
        ]);

        $order = $customer->customOrders()->first();

        $response->assertRedirect(route('customer.orders.show', $order->id));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('custom_orders', [
            'user_id' => $customer->id,
            'catalog_product_id' => $product->id,
            'product_name' => 'Pagar Custom',
            'quantity' => 2,
            'status' => 'pending',
        ]);
        Storage::disk('public')->assertExists($order->reference_design_path);
    }

    public function test_order_creation_validation_rejects_missing_invalid_and_unknown_catalog_data(): void
    {
        $this->fakePublicDisk();
        $customer = $this->customer();

        $response = $this->actingAs($customer)
            ->from(route('customer.orders.create'))
            ->post(route('customer.orders.store'), [
                'product_name' => '',
                'description' => '',
                'dimensions' => '',
                'quantity' => 0,
                'catalog_product_id' => 999,
                'reference_design' => UploadedFile::fake()->create('referensi.txt', 8, 'text/plain'),
            ]);

        $response->assertRedirect(route('customer.orders.create'));
        $response->assertSessionHasErrors([
            'product_name',
            'description',
            'dimensions',
            'quantity',
            'catalog_product_id',
            'reference_design',
        ]);
    }

    public function test_customer_can_only_view_own_order_detail(): void
    {
        $customer = $this->customer();
        $ownOrder = $this->order($customer, ['product_name' => 'Order Milik Saya']);
        $otherOrder = $this->order($this->customer(), ['product_name' => 'Order Orang Lain']);

        $this->actingAs($customer)
            ->get(route('customer.orders.show', $ownOrder->id))
            ->assertOk()
            ->assertSeeText('Order Milik Saya');

        $this->actingAs($customer)
            ->get(route('customer.orders.show', $otherOrder->id))
            ->assertNotFound();
    }
}
