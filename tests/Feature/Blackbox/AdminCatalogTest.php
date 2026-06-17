<?php

namespace Tests\Feature\Blackbox;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AdminCatalogTest extends BlackboxTestCase
{
    public function test_admin_can_create_catalog_product_with_image(): void
    {
        $this->fakePublicDisk();
        $admin = $this->admin();
        $category = $this->category(['name' => 'Pagar']);

        $response = $this->actingAs($admin)->post(route('admin.catalog.store'), [
            'name' => '<b>Pagar Baru</b>',
            'description' => '<p>Deskripsi pagar</p>',
            'category_id' => $category->id,
            'material' => '<span>Besi Hollow</span>',
            'price_estimate' => 750000,
            'image' => UploadedFile::fake()->create('catalog.jpg', 64, 'image/jpeg'),
        ]);

        $product = \App\Models\CatalogProduct::first();

        $response->assertRedirect(route('admin.catalog.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('catalog_products', [
            'name' => 'Pagar Baru',
            'description' => 'Deskripsi pagar',
            'category_id' => $category->id,
            'material' => 'Besi Hollow',
            'price_estimate' => 750000,
            'is_active' => true,
        ]);
        Storage::disk('public')->assertExists($product->image_path);
    }

    public function test_admin_catalog_validation_rejects_invalid_input(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->from(route('admin.catalog.create'))
            ->post(route('admin.catalog.store'), [
                'name' => '',
                'description' => '',
                'category_id' => 999,
                'material' => '',
                'price_estimate' => -1,
                'image' => UploadedFile::fake()->create('catalog.txt', 64, 'text/plain'),
            ]);

        $response->assertRedirect(route('admin.catalog.create'));
        $response->assertSessionHasErrors([
            'name',
            'description',
            'category_id',
            'material',
            'price_estimate',
            'image',
        ]);
    }

    public function test_admin_can_update_and_deactivate_catalog_product(): void
    {
        $admin = $this->admin();
        $oldCategory = $this->category(['name' => 'Pagar']);
        $newCategory = $this->category(['name' => 'Kanopi']);
        $product = $this->product([
            'name' => 'Produk Lama',
            'category_id' => $oldCategory->id,
            'price_estimate' => 500000,
        ]);

        $this->actingAs($admin)->put(route('admin.catalog.update', $product->id), [
            'name' => 'Produk Baru',
            'description' => 'Deskripsi baru',
            'category_id' => $newCategory->id,
            'material' => 'Baja Ringan',
            'price_estimate' => 900000,
        ])->assertRedirect(route('admin.catalog.index'));

        $this->assertDatabaseHas('catalog_products', [
            'id' => $product->id,
            'name' => 'Produk Baru',
            'category_id' => $newCategory->id,
            'material' => 'Baja Ringan',
            'price_estimate' => 900000,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.catalog.destroy', $product->id))
            ->assertRedirect(route('admin.catalog.index'));

        $this->assertDatabaseHas('catalog_products', [
            'id' => $product->id,
            'is_active' => false,
        ]);
    }
}
