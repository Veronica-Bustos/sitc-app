<?php

namespace Database\Seeders;

use App\Models\MaintenanceRecord;
use Illuminate\Database\Seeder;

class MaintenanceRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MaintenanceRecord::factory()->count(5)->create();
    }
}
