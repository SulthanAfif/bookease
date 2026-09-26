<?php

namespace Database\Seeders;

use App\Models\Staff;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $staffs = [
            [
                'name' => 'Budi Santoso',
                'bio' => 'Spesialis potong rambut pria & styling',
                'is_active' => true,
            ],
            [
                'name' => 'Siti Aminah',
                'bio' => 'Ahli coloring & treatment rambut',
                'is_active' => true,
            ],
            [
                'name' => 'Rizky Pratama',
                'bio' => 'Barber berpengalaman 5 tahun',
                'is_active' => true,
            ],
        ];

        foreach ($staffs as $staff) {
            Staff::create($staff);
        }
    }
}