<?php

namespace Database\Seeders;

use App\Models\Holiday;
use App\Helpers\EthiopianCalendar;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    public function run(): void
    {
        $holidays = [
            // 2025 Ethiopian Holidays
            [
                'name' => 'Ethiopian New Year',
                'date' => '2025-09-11',
                'type' => 'national',
                'is_recurring' => true,
                'is_active' => true,
                'description' => 'Enkutatash - Ethiopian New Year celebration',
            ],
            [
                'name' => 'Meskel',
                'date' => '2025-09-27',
                'type' => 'religious',
                'is_recurring' => true,
                'is_active' => true,
                'description' => 'Finding of the True Cross',
            ],
            [
                'name' => 'Ethiopian Christmas',
                'date' => '2026-01-07',
                'type' => 'religious',
                'is_recurring' => true,
                'is_active' => true,
                'description' => 'Genna - Ethiopian Orthodox Christmas',
            ],
            [
                'name' => 'Ethiopian Epiphany',
                'date' => '2026-01-19',
                'type' => 'religious',
                'is_recurring' => true,
                'is_active' => true,
                'description' => 'Timket - Baptism of Jesus celebration',
            ],
            [
                'name' => 'Adwa Victory Day',
                'date' => '2025-03-02',
                'type' => 'national',
                'is_recurring' => true,
                'is_active' => true,
                'description' => 'Victory of Adwa celebration',
            ],
            [
                'name' => 'Good Friday',
                'date' => '2025-04-18',
                'type' => 'religious',
                'is_recurring' => false,
                'is_active' => true,
                'description' => 'Ethiopian Orthodox Good Friday',
            ],
            [
                'name' => 'Easter Sunday',
                'date' => '2025-04-20',
                'type' => 'religious',
                'is_recurring' => false,
                'is_active' => true,
                'description' => 'Ethiopian Orthodox Easter',
            ],
            [
                'name' => 'Labour Day',
                'date' => '2025-05-01',
                'type' => 'national',
                'is_recurring' => true,
                'is_active' => true,
                'description' => 'International Workers Day',
            ],
            [
                'name' => 'Patriots Victory Day',
                'date' => '2025-05-05',
                'type' => 'national',
                'is_recurring' => true,
                'is_active' => true,
                'description' => 'Liberation Day',
            ],
            [
                'name' => 'Derg Downfall Day',
                'date' => '2025-05-28',
                'type' => 'national',
                'is_recurring' => true,
                'is_active' => true,
                'description' => 'End of Derg regime',
            ],
        ];

        foreach ($holidays as $holiday) {
            $holiday['ethiopian_date'] = EthiopianCalendar::format($holiday['date']);
            Holiday::create($holiday);
        }
    }
}
