<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Potong Rambut',
                'description' => 'Potong rambut pria / wanita standar',
                'price' => 50000,
                'duration' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Cuci + Blow',
                'description' => 'Cuci rambut + blow dry',
                'price' => 35000,
                'duration' => 25,
                'is_active' => true,
            ],
            [
                'name' => 'Coloring',
                'description' => 'Pewarnaan rambut',
                'price' => 150000,
                'duration' => 90,
                'is_active' => true,
            ],
            [
                'name' => 'Creambath',
                'description' => 'Creambath + pijat kepala',
                'price' => 80000,
                'duration' => 45,
                'is_active' => true,
            ],
            [
                'name' => 'Hair Spa',
                'description' => 'Perawatan rambut menyeluruh',
                'price' => 120000,
                'duration' => 60,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
