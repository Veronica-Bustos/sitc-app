<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating permissions...');

        foreach (PermissionEnum::cases() as $permissionEnum) {
            Permission::firstOrCreate(
                ['name' => $permissionEnum->value],
                ['guard_name' => 'web']
            );
        }

        $this->command->info('Created '.count(PermissionEnum::cases()).' permissions successfully.');
    }
}
