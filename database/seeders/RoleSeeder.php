<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, create all permissions
        $this->call(PermissionsSeeder::class);

        $this->command->info('Creating roles and assigning permissions...');

        // Create all roles and assign permissions based on the matrix
        foreach (RoleEnum::cases() as $roleEnum) {
            $role = Role::firstOrCreate(
                ['name' => $roleEnum->value],
                ['guard_name' => 'web']
            );

            // Assign permissions based on role
            $permissions = $this->getPermissionsForRole($roleEnum);
            $role->syncPermissions($permissions);

            $this->command->info("Role '{$roleEnum->value}' created with ".count($permissions).' permissions.');
        }

        $this->command->info('All roles and permissions configured successfully.');
    }

    /**
     * Get permissions for a specific role based on the permission matrix.
     *
     * @return array<int, string>
     */
    private function getPermissionsForRole(RoleEnum $roleEnum): array
    {
        return match ($roleEnum) {
            RoleEnum::ADMIN => $this->getAdminPermissions(),
            RoleEnum::ALMACENISTA => $this->getAlmacenistaPermissions(),
            RoleEnum::JEFE_OBRA => $this->getJefeObraPermissions(),
            RoleEnum::TECNICO => $this->getTecnicoPermissions(),
            RoleEnum::AUDITOR => $this->getAuditorPermissions(),
            RoleEnum::INACTIVO => [],
        };
    }

    /**
     * Admin has ALL permissions.
     *
     * @return array<int, string>
     */
    private function getAdminPermissions(): array
    {
        return PermissionEnum::values();
    }

    /**
     * Almacenista permissions based on matrix.
     *
     * @return array<int, string>
     */
    private function getAlmacenistaPermissions(): array
    {
        return [
            // Dashboard
            PermissionEnum::DASHBOARD_VIEW->value,

            // Items: view, create, edit (no delete)
            PermissionEnum::ITEMS_VIEW->value,
            PermissionEnum::ITEMS_CREATE->value,
            PermissionEnum::ITEMS_EDIT->value,
            PermissionEnum::ITEMS_HISTORY->value,

            // Categories: view only
            PermissionEnum::CATEGORIES_VIEW->value,

            // Locations: view, create, edit (no delete)
            PermissionEnum::LOCATIONS_VIEW->value,
            PermissionEnum::LOCATIONS_CREATE->value,
            PermissionEnum::LOCATIONS_EDIT->value,

            // Inventory Movements: full except delete
            PermissionEnum::INVENTORY_MOVEMENTS_VIEW->value,
            PermissionEnum::INVENTORY_MOVEMENTS_CREATE->value,
            PermissionEnum::INVENTORY_MOVEMENTS_EDIT->value,

            // Maintenance: view, create, edit
            PermissionEnum::MAINTENANCE_RECORDS_VIEW->value,
            PermissionEnum::MAINTENANCE_RECORDS_CREATE->value,
            PermissionEnum::MAINTENANCE_RECORDS_EDIT->value,

            // Attachments: full
            PermissionEnum::ATTACHMENTS_VIEW->value,
            PermissionEnum::ATTACHMENTS_CREATE->value,
            PermissionEnum::ATTACHMENTS_EDIT->value,
            PermissionEnum::ATTACHMENTS_DELETE->value,

            // Reports: view only
            PermissionEnum::REPORTS_STOCK->value,
            PermissionEnum::REPORTS_MOVEMENTS->value,
            PermissionEnum::REPORTS_OUT_OF_SERVICE->value,

            // Settings: all
            PermissionEnum::SETTINGS_PROFILE->value,
            PermissionEnum::SETTINGS_PASSWORD->value,
            PermissionEnum::SETTINGS_APPEARANCE->value,
        ];
    }

    /**
     * Jefe de Obra permissions based on matrix.
     *
     * @return array<int, string>
     */
    private function getJefeObraPermissions(): array
    {
        return [
            // Dashboard
            PermissionEnum::DASHBOARD_VIEW->value,

            // Items: view only
            PermissionEnum::ITEMS_VIEW->value,
            PermissionEnum::ITEMS_HISTORY->value,

            // Categories: view only
            PermissionEnum::CATEGORIES_VIEW->value,

            // Locations: view only
            PermissionEnum::LOCATIONS_VIEW->value,

            // Inventory Movements: view and create
            PermissionEnum::INVENTORY_MOVEMENTS_VIEW->value,
            PermissionEnum::INVENTORY_MOVEMENTS_CREATE->value,

            // Maintenance: view and create
            PermissionEnum::MAINTENANCE_RECORDS_VIEW->value,
            PermissionEnum::MAINTENANCE_RECORDS_CREATE->value,

            // Attachments: view and create
            PermissionEnum::ATTACHMENTS_VIEW->value,
            PermissionEnum::ATTACHMENTS_CREATE->value,

            // Reports: view only
            PermissionEnum::REPORTS_STOCK->value,
            PermissionEnum::REPORTS_MOVEMENTS->value,
            PermissionEnum::REPORTS_OUT_OF_SERVICE->value,

            // Settings: all
            PermissionEnum::SETTINGS_PROFILE->value,
            PermissionEnum::SETTINGS_PASSWORD->value,
            PermissionEnum::SETTINGS_APPEARANCE->value,
        ];
    }

    /**
     * Técnico permissions based on matrix.
     *
     * @return array<int, string>
     */
    private function getTecnicoPermissions(): array
    {
        return [
            // Dashboard
            PermissionEnum::DASHBOARD_VIEW->value,

            // Items: view only
            PermissionEnum::ITEMS_VIEW->value,
            PermissionEnum::ITEMS_HISTORY->value,

            // Categories: view only
            PermissionEnum::CATEGORIES_VIEW->value,

            // Locations: view only
            PermissionEnum::LOCATIONS_VIEW->value,

            // Inventory Movements: view only
            PermissionEnum::INVENTORY_MOVEMENTS_VIEW->value,

            // Maintenance: full except delete
            PermissionEnum::MAINTENANCE_RECORDS_VIEW->value,
            PermissionEnum::MAINTENANCE_RECORDS_CREATE->value,
            PermissionEnum::MAINTENANCE_RECORDS_EDIT->value,

            // Attachments: view and create
            PermissionEnum::ATTACHMENTS_VIEW->value,
            PermissionEnum::ATTACHMENTS_CREATE->value,

            // Reports: view only
            PermissionEnum::REPORTS_STOCK->value,
            PermissionEnum::REPORTS_MOVEMENTS->value,
            PermissionEnum::REPORTS_OUT_OF_SERVICE->value,

            // Settings: all
            PermissionEnum::SETTINGS_PROFILE->value,
            PermissionEnum::SETTINGS_PASSWORD->value,
            PermissionEnum::SETTINGS_APPEARANCE->value,
        ];
    }

    /**
     * Auditor permissions based on matrix.
     *
     * @return array<int, string>
     */
    private function getAuditorPermissions(): array
    {
        return [
            // Dashboard
            PermissionEnum::DASHBOARD_VIEW->value,

            // Items: view only
            PermissionEnum::ITEMS_VIEW->value,
            PermissionEnum::ITEMS_HISTORY->value,

            // Categories: view only
            PermissionEnum::CATEGORIES_VIEW->value,

            // Locations: view only
            PermissionEnum::LOCATIONS_VIEW->value,

            // Inventory Movements: view only
            PermissionEnum::INVENTORY_MOVEMENTS_VIEW->value,

            // Maintenance: view only
            PermissionEnum::MAINTENANCE_RECORDS_VIEW->value,

            // Attachments: view only
            PermissionEnum::ATTACHMENTS_VIEW->value,

            // Reports: full (auditors need reports)
            PermissionEnum::REPORTS_STOCK->value,
            PermissionEnum::REPORTS_MOVEMENTS->value,
            PermissionEnum::REPORTS_OUT_OF_SERVICE->value,

            // Settings: all
            PermissionEnum::SETTINGS_PROFILE->value,
            PermissionEnum::SETTINGS_PASSWORD->value,
            PermissionEnum::SETTINGS_APPEARANCE->value,
        ];
    }
}
