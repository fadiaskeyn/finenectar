<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Fine Nectar Honey 200ml',
                'description' => 'Madu murni premium untuk rutinitas pagi, campuran minuman, dan energi harian.',
                'price' => 35000,
                'compare_at_price' => 55000,
                'net_weight' => '200ml',
                'weight_grams' => 1000,
                'category' => 'Madu konsumsi harian',
                'stock' => 100,
                'is_active' => true,
                'is_featured' => true,
                'is_promo' => true,
                'is_free_shipping' => false,
            ],
            [
                'name' => 'Fine Nectar Daily Bundle',
                'description' => 'Paket hemat untuk stok madu di rumah atau kantor.',
                'price' => 99000,
                'compare_at_price' => 165000,
                'net_weight' => '3 x 200ml',
                'weight_grams' => 3000,
                'category' => 'Bundle',
                'stock' => 40,
                'is_active' => true,
                'is_featured' => true,
                'is_promo' => true,
                'is_free_shipping' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => Str::slug($product['name'])],
                $product + ['slug' => Str::slug($product['name'])]
            );
        }
    }
}
