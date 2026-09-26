<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\Staff;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $staffs = Staff::all();

        // Jadwal Senin - Sabtu (1-6), Minggu libur
        foreach ($staffs as $staff) {
            for ($day = 1; $day <= 6; $day++) {
                Schedule::create([
                    'staff_id' => $staff->id,
                    'day_of_week' => $day,
                    'start_time' => '09:00:00',
                    'end_time' => '18:00:00',
                    'is_day_off' => false,
                ]);
            }

            // Minggu libur
            Schedule::create([
                'staff_id' => $staff->id,
                'day_of_week' => 0,
                'start_time' => '00:00:00',
                'end_time' => '00:00:00',
                'is_day_off' => true,
            ]);
        }
    }
}