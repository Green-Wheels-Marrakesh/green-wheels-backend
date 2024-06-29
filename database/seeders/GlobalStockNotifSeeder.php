<?php

namespace Database\Seeders;

use App\Enums\SettingEnum;
use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GlobalStockNotifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'setting_key' => SettingEnum::GLOBAL_STOCK_NOTIF(),
            'setting_value' => 0,
        ]);
    }
}
