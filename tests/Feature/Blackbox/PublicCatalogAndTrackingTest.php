<?php

namespace Tests\Feature\Blackbox;

use App\Models\CatalogProduct;
use App\Models\Category;
use App\Models\CustomOrder;

class PublicCatalogAndTrackingTest extends BlackboxTestCase
{
    public function test_homepage_shows_active_catalog_products(): void
    {
        $active = $this->product(['name' => 'Pagar Minimalis Premium']);
        $inactive = $this->product(['name' => 'Produk Nonaktif', 'is_active' => false]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSeeText('Pagar Minimalis Premium');
        $response->assertDontSeeText('Produk Nonaktif');
    }

    public function test_catalog_index_shows_active_products_and_category_filters(): void
    {
        $pagar = $this->category(['name' => 'Pagar']);
        $kanopi = $this->category(['name' => 'Kanopi']);
        $pagarProduct = $this->product(['name' => 'Pagar Besi Modern', 'category_id' => $pagar->id]);
        $kanopiProduct = $this->product(['name' => 'Kanopi Baja Ringan', 'category_id' => $kanopi->id]);
        $this->product(['name' => 'Produk Nonaktif', 'is_active' => false]);

        $response = $this->get(route('catalog.index'));

        $response->assertOk();
        $response->assertSeeText('Pagar');
        $response->assertSeeText('Kanopi');
        $response->assertSeeText('Pagar Besi Modern');
        $response->assertSeeText('Kanopi Baja Ringan');
        $response->assertDontSeeText('Produk Nonaktif');

        $filtered = $this->get(route('catalog.index', ['category' => $pagar->id]));

        $filtered->assertOk();
        $filtered->assertSeeText($pagarProduct->name);
        $filtered->assertDontSeeText($kanopiProduct->name);
    }

    public function test_catalog_detail_shows_category_name_and_product_information(): void
    {
        $category = $this->category(['name' => 'Custom']);
        $product = $this->product([
            'name' => 'Custom Metalwork',
            'description' => 'Pembuatan konstruksi besi custom.',
            'category_id' => $category->id,
            'material' => 'Sesuai Permintaan',
            'price_estimate' => 500000,
        ]);

        $response = $this->get(route('catalog.show', $product->id));

        $response->assertOk();
        $response->assertSeeText('Custom Metalwork');
        $response->assertSeeText('Custom');
        $response->assertSeeText('Sesuai Permintaan');
        $response->assertDontSee('{"id":', false);
    }

    public function test_inactive_catalog_product_detail_returns_404(): void
    {
        $product = CatalogProduct::factory()->inactive()->create();

        $this->get(route('catalog.show', $product->id))->assertNotFound();
    }

    public function test_catalog_detail_order_link_uses_prefill_route_parameter(): void
    {
        $product = $this->product(['name' => 'Railing Tangga']);

        $response = $this->get(route('catalog.show', $product->id));

        $response->assertOk();
        $response->assertSee(route('customer.orders.create', $product->id), false);
    }

    public function test_tracking_shows_found_and_not_found_order_states(): void
    {
        $order = $this->order(null, [
            'order_code' => 'ORD-TRACK1',
            'product_name' => 'Pintu Besi',
            'status' => 'confirmed',
            'total_price' => 1500000,
        ]);

        $found = $this->get(route('orders.track', ['order_code' => $order->order_code]));

        $found->assertOk();
        $found->assertSeeText('Pesanan Ditemukan');
        $found->assertSeeText('ORD-TRACK1');
        $found->assertSeeText('Pintu Besi');

        $missing = $this->get(route('orders.track', ['order_code' => 'ORD-NOTFOUND']));

        $missing->assertOk();
        $missing->assertSeeText('Pesanan Tidak Ditemukan');
    }
}
