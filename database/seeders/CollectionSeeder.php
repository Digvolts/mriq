<?php

namespace Database\Seeders;

use App\Models\Collection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $collections = [
            ['name' => 'Persija', 'icon' => '🛡️'],
            ['name' => 'World Cup', 'icon' => '🏆'],
            ['name' => 'Anime', 'icon' => '⭐'],
            ['name' => 'Limited Edition', 'icon' => '✨'],
            ['name' => 'Sports', 'icon' => '⚽'],
        ];

        foreach ($collections as $collection) {
            Collection::create([
                ...$collection,
                'is_active' => true,
            ]);
        }
    }
}
