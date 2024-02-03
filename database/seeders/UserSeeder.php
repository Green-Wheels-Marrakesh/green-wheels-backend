<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countAdmins = 5;
        $countEmployees = 30;
        Admin::factory()
            ->count($countAdmins)
            ->create();
        Employee::factory()
            ->count($countEmployees)
            ->create();
    }
}
