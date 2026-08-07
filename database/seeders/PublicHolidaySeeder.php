<?php

namespace Database\Seeders;

use App\Models\PublicHoliday;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PublicHolidaySeeder extends Seeder
{
    public function run(): void
    {
        $year = Carbon::now()->year;
        $holidays = [
            "{$year}-01-26" => 'Republic Day',
            "{$year}-03-08" => 'Holi',
            "{$year}-03-14" => 'Holi (Second Day)',
            "{$year}-04-14" => 'Ambedkar Jayanti',
            "{$year}-05-01" => 'Labour Day',
            "{$year}-08-15" => 'Independence Day',
            "{$year}-10-02" => 'Gandhi Jayanti',
            "{$year}-10-31" => 'Diwali',
            "{$year}-11-01" => 'Diwali (Second Day)',
            "{$year}-12-25" => 'Christmas',
        ];

        foreach ($holidays as $date => $name) {
            PublicHoliday::updateOrCreate(
                ['date' => $date],
                ['name' => $name, 'is_active' => true]
            );
        }
    }
}
