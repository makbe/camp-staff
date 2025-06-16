<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shifts = [
            [
                'name' => 'Летняя смена 2025 - 1 поток',
                'start_date' => '2025-06-01',
                'end_date' => '2025-06-21',
            ],
            [
                'name' => 'Летняя смена 2025 - 2 поток',
                'start_date' => '2025-06-25',
                'end_date' => '2025-07-15',
            ],
            [
                'name' => 'Летняя смена 2025 - 3 поток',
                'start_date' => '2025-07-20',
                'end_date' => '2025-08-10',
            ],
            [
                'name' => 'Осенняя смена 2025',
                'start_date' => '2025-10-26',
                'end_date' => '2025-11-02',
            ],
        ];

        foreach ($shifts as $shift) {
            Shift::create($shift);
        }
    }
}
