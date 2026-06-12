<?php

namespace Database\Seeders;

use App\Models\CatalogProduct;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin Bengkel',
            'email' => 'admin@bengkelasyraf.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Raya Talaga No. 45, Majalengka, Jawa Barat',
        ]);

        // Sample customers
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '081298765432',
            'address' => 'Jl. Merdeka No. 12, Talaga, Majalengka',
        ]);

        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '085612345678',
            'address' => 'Jl. Sudirman No. 78, Cikijing, Majalengka',
        ]);

        // Categories
        $catPagar = \App\Models\Category::create(['name' => 'Pagar']);
        $catKanopi = \App\Models\Category::create(['name' => 'Kanopi']);
        $catTeralis = \App\Models\Category::create(['name' => 'Teralis']);
        $catRailing = \App\Models\Category::create(['name' => 'Railing']);
        $catPintu = \App\Models\Category::create(['name' => 'Pintu']);
        $catCustom = \App\Models\Category::create(['name' => 'Custom']);

        // Catalog products
        CatalogProduct::create([
            'name' => 'Pagar Besi Minimalis',
            'description' => 'Pagar besi hollow minimalis dengan desain modern dan elegan. Cocok untuk rumah tinggal bergaya minimalis. Menggunakan besi hollow berkualitas tinggi dengan finishing cat anti karat.',
            'category_id' => $catPagar->id,
            'material' => 'Besi Hollow 40x40mm',
            'price_estimate' => 850000.00,
            'is_active' => true,
        ]);

        CatalogProduct::create([
            'name' => 'Kanopi Baja Ringan',
            'description' => 'Kanopi baja ringan dengan atap spandek atau polikarbonat. Tahan terhadap cuaca ekstrem dan perawatan mudah. Tersedia berbagai ukuran sesuai kebutuhan carport atau teras rumah Anda.',
            'category_id' => $catKanopi->id,
            'material' => 'Baja Ringan + Spandek',
            'price_estimate' => 650000.00,
            'is_active' => true,
        ]);

        CatalogProduct::create([
            'name' => 'Teralis Jendela Klasik',
            'description' => 'Teralis jendela dengan motif klasik yang memberikan keamanan ekstra untuk rumah Anda. Dibuat dari besi solid dengan las yang rapi dan kuat. Finishing cat anti karat warna hitam atau putih.',
            'category_id' => $catTeralis->id,
            'material' => 'Besi Solid 12mm',
            'price_estimate' => 450000.00,
            'is_active' => true,
        ]);

        CatalogProduct::create([
            'name' => 'Railing Tangga Stainless',
            'description' => 'Railing tangga berbahan stainless steel 304 anti karat. Desain modern dan kokoh dengan tiang penyangga yang kuat. Cocok untuk tangga dalam maupun luar ruangan.',
            'category_id' => $catRailing->id,
            'material' => 'Stainless Steel 304',
            'price_estimate' => 750000.00,
            'is_active' => true,
        ]);

        CatalogProduct::create([
            'name' => 'Pintu Besi Henderson',
            'description' => 'Pintu besi geser model henderson untuk garasi atau gudang. Menggunakan rel henderson berkualitas tinggi untuk bukaan yang halus dan ringan. Dilengkapi kunci pengaman ganda.',
            'category_id' => $catPintu->id,
            'material' => 'Plat Besi 1.2mm + Rangka Hollow',
            'price_estimate' => 2500000.00,
            'is_active' => true,
        ]);

        CatalogProduct::create([
            'name' => 'Custom Metalwork',
            'description' => 'Pembuatan konstruksi besi custom sesuai permintaan pelanggan. Meliputi rak besi, meja las, rangka atap, dan berbagai kebutuhan fabrikasi logam lainnya. Konsultasi desain gratis.',
            'category_id' => $catCustom->id,
            'material' => 'Sesuai Permintaan',
            'price_estimate' => 500000.00,
            'is_active' => true,
        ]);
    }
}
