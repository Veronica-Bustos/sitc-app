<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Veronica Bustos',
                'email' => 'admin@example.com',
                'role' => RoleEnum::ADMIN->value,
            ],
            [
                'name' => 'Javier Mendez',
                'email' => 'almacenista@sitc.test',
                'role' => RoleEnum::ALMACENISTA->value,
            ],
            [
                'name' => 'Paula Ortega',
                'email' => 'jefeobra@sitc.test',
                'role' => RoleEnum::JEFE_OBRA->value,
            ],
            [
                'name' => 'Sergio Luna',
                'email' => 'tecnico@sitc.test',
                'role' => RoleEnum::TECNICO->value,
            ],
            [
                'name' => 'Lucia Herrera',
                'email' => 'auditor@sitc.test',
                'role' => RoleEnum::AUDITOR->value,
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            $user->syncRoles([$userData['role']]);
        }
    }
}
