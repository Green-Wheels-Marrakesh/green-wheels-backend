<?php

namespace Database\Seeders;

use App\Enums\SettingEnum;
use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingCalendarColorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'setting_key' => SettingEnum::BOOKING_CALENDAR_COLORS(),
            'setting_value' => collect([
                [
                    'start_time' => '00:00',
                    'end_time' => '01:00',
                    'color' => '#000000',
                ],
            ])->toJson(),
        ]);
    }
}
