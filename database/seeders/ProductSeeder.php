<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'collection_id' => 1,
                'name' => 'Luffy is Persija',
                'description' => 'Kaos premium dengan desain Luffy dan logo Persija',
                'price' => 199000,
                'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&h=600&fit=crop',
                'color' => 'Red',
                'size' => 'M',
            ],
            [
                'collection_id' => 2,
                'name' => 'World Cup 2024',
                'description' => 'Kaos resmi World Cup 2024',
                'price' => 249000,
                'image' => 'https://images.unsplash.com/photo-1503341455253-b2e723bb12dd?w=800&h=600&fit=crop',
                'color' => 'Yellow',
                'size' => 'L',
            ],
            [
                'collection_id' => 3,
                'name' => 'Luffy Anime',
                'description' => 'Kaos anime One Piece dengan Luffy',
                'price' => 199000,
                'image' => 'https://images.unsplash.com/photo-1552157471-44a51c6b0f31?w=800&h=600&fit=crop',
                'color' => 'Black',
                'size' => 'M',
            ],
            [
                'collection_id' => 3,
                'name' => 'Naruto Anime',
                'description' => 'Kaos anime Naruto premium',
                'price' => 199000,
                'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&h=600&fit=crop',
                'color' => 'Orange',
                'size' => 'L',
            ],
            [
                'collection_id' => 4,
                'name' => 'Limited Gold Edition',
                'description' => 'Kaos edisi terbatas dengan bahan premium',
                'price' => 349000,
                'image' => 'https://images.unsplash.com/photo-1503341455253-b2e723bb12dd?w=800&h=600&fit=crop',
                'color' => 'Gold',
                'size' => 'XL',
            ],
            [
                'collection_id' => 5,
                'name' => 'Football Classic',
                'description' => 'Kaos olahraga dengan desain klasik',
                'price' => 179000,
                'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&h=600&fit=crop',
                'color' => 'White',
                'size' => 'M',
            ],
            [
                'collection_id' => 1,
                'name' => 'Persija Heritage',
                'description' => 'Kaos heritage Persija dengan desain retro',
                'price' => 249000,
                'image' => 'https://images.unsplash.com/photo-1552157471-44a51c6b0f31?w=800&h=600&fit=crop',
                'color' => 'Red',
                'size' => 'L',
            ],
            [
                'collection_id' => 2,
                'name' => 'Brazil Jersey',
                'description' => 'Jersey Brazil resmi',
                'price' => 299000,
                'image' => 'https://images.unsplash.com/photo-1503341455253-b2e723bb12dd?w=800&h=600&fit=crop',
                'color' => 'Yellow',
                'size' => 'M',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}

