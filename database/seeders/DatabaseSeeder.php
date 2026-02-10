<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class,
            CategorySeeder::class,
            LocationSeeder::class,
            ItemSeeder::class,
            ItemHistorySeeder::class,
        ]);

        $this->command->info('Importing data into Scout...');

        foreach (
            [
                \App\Models\Category::class,
                \App\Models\Location::class,
                \App\Models\Item::class,
            ] as $model
        ) {
            Artisan::call('scout:import', ['model' => $model]);
            $this->command->info("Imported: {$model}");
        }
    }
}
