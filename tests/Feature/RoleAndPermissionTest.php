<?php

use App\Enums\RoleEnum;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

uses()->group('roles');

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

describe('Role and Permission System', function () {
    describe('Roles Seeder', function () {
        it('creates all system roles in database', function () {
            foreach (RoleEnum::cases() as $roleEnum) {
                expect(Role::where('name', $roleEnum->value)->exists())->toBeTrue();
            }
        });

        it('creates roles with web guard', function () {
            $role = Role::where('name', RoleEnum::ADMIN->value)->first();

            expect($role->guard_name)->toBe('web');
        });
    });

    describe('Admin User Seeder', function () {
        beforeEach(function () {
            $this->seed(\Database\Seeders\AdminUserSeeder::class);
        });

        it('creates admin user with correct credentials', function () {
            $admin = User::where('email', 'admin@example.com')->first();

            expect($admin)->not->toBeNull();
            expect($admin->name)->toBe('Veronica Bustos');
            expect(Hash::check('password', $admin->password))->toBeTrue();
            expect($admin->email_verified_at)->not->toBeNull();
        });

        it('assigns admin role to admin user', function () {
            $admin = User::where('email', 'admin@example.com')->first();

            expect($admin->hasRole(RoleEnum::ADMIN->value))->toBeTrue();
        });
    });

    describe('User Registration', function () {
        it('assigns inactive role to newly registered users', function () {
            $response = $this->post(route('register'), [
                'name' => 'Test User',
                'email' => 'testuser@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

            $response->assertRedirect(route('dashboard'));

            $user = User::where('email', 'testuser@example.com')->first();

            expect($user)->not->toBeNull();
            expect($user->hasRole(RoleEnum::INACTIVO->value))->toBeTrue();
            expect($user->hasAnyRole([
                RoleEnum::ADMIN->value,
                RoleEnum::ALMACENISTA->value,
                RoleEnum::JEFE_OBRA->value,
                RoleEnum::TECNICO->value,
                RoleEnum::AUDITOR->value,
            ]))->toBeFalse();
        });

        it('allows user to login after registration', function () {
            $this->post(route('register'), [
                'name' => 'Test User',
                'email' => 'testuser2@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

            $this->assertAuthenticated();
        });
    });

    describe('RoleEnum', function () {
        it('returns correct labels for all roles', function () {
            expect(RoleEnum::INACTIVO->label())->toBe('Inactivo');
            expect(RoleEnum::ADMIN->label())->toBe('Administrador');
            expect(RoleEnum::ALMACENISTA->label())->toBe('Almacenista');
            expect(RoleEnum::JEFE_OBRA->label())->toBe('Jefe de Obra');
            expect(RoleEnum::TECNICO->label())->toBe('Técnico');
            expect(RoleEnum::AUDITOR->label())->toBe('Auditor');
        });

        it('returns assignable roles excluding inactive', function () {
            $assignable = RoleEnum::assignableRoles();

            expect($assignable)->toHaveCount(5);
            expect($assignable)->not->toHaveKey(RoleEnum::INACTIVO->value);
            expect($assignable)->toHaveKey(RoleEnum::ADMIN->value);
            expect($assignable)->toHaveKey(RoleEnum::ALMACENISTA->value);
            expect($assignable)->toHaveKey(RoleEnum::JEFE_OBRA->value);
            expect($assignable)->toHaveKey(RoleEnum::TECNICO->value);
            expect($assignable)->toHaveKey(RoleEnum::AUDITOR->value);
        });
    });
});
