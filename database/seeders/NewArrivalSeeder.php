<?php

namespace Database\Seeders;

use App\Models\newArrivals;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewArrivalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $newArrivals = [
            [
                'name' => 'Luffy is Persija',
                'image' => 'https://loremflickr.com/320/240',
            ],
            [
                'name' => 'Luffy is Versija',
                'image' => 'https://loremflickr.com/320/240/dog',
            ],
            [
                'name' => 'Luffy Anime',
                'image' => 'https://loremflickr.com/g/320/240/paris',
            ],
                [
                'name' => 'Luffy Anime',
                'image' => 'https://loremflickr.com/320/240/brazil,rio',
            ],
                [
                'name' => 'Luffy Anime',
                'image' => 'https://loremflickr.com/320/240/paris,girl/all',
            ],
        ];

        foreach ($newArrivals as $arrival) {
            newArrivals::create([
                ...$arrival,
                'is_active' => true,
            ]);
        }
            }
}
