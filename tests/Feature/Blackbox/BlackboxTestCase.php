<?php

namespace Tests\Feature\Blackbox;

use App\Models\CatalogProduct;
use App\Models\Category;
use App\Models\CustomOrder;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

abstract class BlackboxTestCase extends TestCase
{
    use RefreshDatabase;

    protected function admin(array $attributes = []): User
    {
        return User::factory()->admin()->create($attributes);
    }

    protected function customer(array $attributes = []): User
    {
        return User::factory()->customer()->create($attributes);
    }

    protected function category(array $attributes = []): Category
    {
        return Category::factory()->create($attributes);
    }

    protected function product(array $attributes = []): CatalogProduct
    {
        return CatalogProduct::factory()->create($attributes);
    }

    protected function order(?User $user = null, array $attributes = []): CustomOrder
    {
        return CustomOrder::factory()->create(array_merge([
            'user_id' => ($user ?? $this->customer())->id,
        ], $attributes));
    }

    protected function payment(?CustomOrder $order = null, array $attributes = []): Payment
    {
        return Payment::factory()->create(array_merge([
            'custom_order_id' => ($order ?? $this->order())->id,
        ], $attributes));
    }

    protected function fakePublicDisk(): void
    {
        $root = sys_get_temp_dir().DIRECTORY_SEPARATOR.'website_pemesanan_jasa_las_blackbox_public';

        File::ensureDirectoryExists($root);
        File::cleanDirectory($root);

        config(['filesystems.disks.public.root' => $root]);
        Storage::forgetDisk('public');
    }
}
