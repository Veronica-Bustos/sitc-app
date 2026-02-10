<?php

namespace App\Enums;

enum PermissionEnum: string
{
    // Dashboard
    case DASHBOARD_VIEW = 'dashboard.view';

    // Items (Inventario)
    case ITEMS_VIEW = 'items.view';
    case ITEMS_CREATE = 'items.create';
    case ITEMS_EDIT = 'items.edit';
    case ITEMS_DELETE = 'items.delete';
    case ITEMS_HISTORY = 'items.history';

    // Categories (Categorías)
    case CATEGORIES_VIEW = 'categories.view';
    case CATEGORIES_CREATE = 'categories.create';
    case CATEGORIES_EDIT = 'categories.edit';
    case CATEGORIES_DELETE = 'categories.delete';

    // Locations (Ubicaciones)
    case LOCATIONS_VIEW = 'locations.view';
    case LOCATIONS_CREATE = 'locations.create';
    case LOCATIONS_EDIT = 'locations.edit';
    case LOCATIONS_DELETE = 'locations.delete';

    // Inventory Movements (Movimientos)
    case INVENTORY_MOVEMENTS_VIEW = 'inventory-movements.view';
    case INVENTORY_MOVEMENTS_CREATE = 'inventory-movements.create';
    case INVENTORY_MOVEMENTS_EDIT = 'inventory-movements.edit';
    case INVENTORY_MOVEMENTS_DELETE = 'inventory-movements.delete';

    // Maintenance Records (Mantenimiento)
    case MAINTENANCE_RECORDS_VIEW = 'maintenance-records.view';
    case MAINTENANCE_RECORDS_CREATE = 'maintenance-records.create';
    case MAINTENANCE_RECORDS_EDIT = 'maintenance-records.edit';
    case MAINTENANCE_RECORDS_DELETE = 'maintenance-records.delete';

    // Attachments (Medios)
    case ATTACHMENTS_VIEW = 'attachments.view';
    case ATTACHMENTS_CREATE = 'attachments.create';
    case ATTACHMENTS_EDIT = 'attachments.edit';
    case ATTACHMENTS_DELETE = 'attachments.delete';

    // Reports (Reportes)
    case REPORTS_STOCK = 'reports.stock';
    case REPORTS_MOVEMENTS = 'reports.movements';
    case REPORTS_OUT_OF_SERVICE = 'reports.out-of-service';

    // Settings (Configuración)
    case SETTINGS_PROFILE = 'settings.profile';
    case SETTINGS_PASSWORD = 'settings.password';
    case SETTINGS_APPEARANCE = 'settings.appearance';

    /**
     * Get all permissions as an array.
     *
     * @return array<string, string>
     */
    public static function all(): array
    {
        return array_reduce(
            self::cases(),
            fn (array $carry, self $permission) => $carry + [$permission->value => $permission->label()],
            []
        );
    }

    /**
     * Get the display name for the permission.
     */
    public function label(): string
    {
        return match ($this) {
            // Dashboard
            self::DASHBOARD_VIEW => 'Ver Dashboard',

            // Items
            self::ITEMS_VIEW => 'Ver Ítems',
            self::ITEMS_CREATE => 'Crear Ítems',
            self::ITEMS_EDIT => 'Editar Ítems',
            self::ITEMS_DELETE => 'Eliminar Ítems',
            self::ITEMS_HISTORY => 'Ver Historial de Ítems',

            // Categories
            self::CATEGORIES_VIEW => 'Ver Categorías',
            self::CATEGORIES_CREATE => 'Crear Categorías',
            self::CATEGORIES_EDIT => 'Editar Categorías',
            self::CATEGORIES_DELETE => 'Eliminar Categorías',

            // Locations
            self::LOCATIONS_VIEW => 'Ver Ubicaciones',
            self::LOCATIONS_CREATE => 'Crear Ubicaciones',
            self::LOCATIONS_EDIT => 'Editar Ubicaciones',
            self::LOCATIONS_DELETE => 'Eliminar Ubicaciones',

            // Inventory Movements
            self::INVENTORY_MOVEMENTS_VIEW => 'Ver Movimientos',
            self::INVENTORY_MOVEMENTS_CREATE => 'Crear Movimientos',
            self::INVENTORY_MOVEMENTS_EDIT => 'Editar Movimientos',
            self::INVENTORY_MOVEMENTS_DELETE => 'Eliminar Movimientos',

            // Maintenance Records
            self::MAINTENANCE_RECORDS_VIEW => 'Ver Mantenimientos',
            self::MAINTENANCE_RECORDS_CREATE => 'Crear Mantenimientos',
            self::MAINTENANCE_RECORDS_EDIT => 'Editar Mantenimientos',
            self::MAINTENANCE_RECORDS_DELETE => 'Eliminar Mantenimientos',

            // Attachments
            self::ATTACHMENTS_VIEW => 'Ver Adjuntos',
            self::ATTACHMENTS_CREATE => 'Crear Adjuntos',
            self::ATTACHMENTS_EDIT => 'Editar Adjuntos',
            self::ATTACHMENTS_DELETE => 'Eliminar Adjuntos',

            // Reports
            self::REPORTS_STOCK => 'Ver Reporte de Stock',
            self::REPORTS_MOVEMENTS => 'Ver Reporte de Movimientos',
            self::REPORTS_OUT_OF_SERVICE => 'Ver Reporte Fuera de Servicio',

            // Settings
            self::SETTINGS_PROFILE => 'Configurar Perfil',
            self::SETTINGS_PASSWORD => 'Cambiar Contraseña',
            self::SETTINGS_APPEARANCE => 'Configurar Apariencia',
        };
    }

    /**
     * Get permissions grouped by module.
     *
     * @return array<string, array<string, string>>
     */
    public static function byModule(): array
    {
        return [
            'Dashboard' => [
                self::DASHBOARD_VIEW->value => self::DASHBOARD_VIEW->label(),
            ],
            'Inventario' => [
                self::ITEMS_VIEW->value => self::ITEMS_VIEW->label(),
                self::ITEMS_CREATE->value => self::ITEMS_CREATE->label(),
                self::ITEMS_EDIT->value => self::ITEMS_EDIT->label(),
                self::ITEMS_DELETE->value => self::ITEMS_DELETE->label(),
                self::ITEMS_HISTORY->value => self::ITEMS_HISTORY->label(),
            ],
            'Categorías' => [
                self::CATEGORIES_VIEW->value => self::CATEGORIES_VIEW->label(),
                self::CATEGORIES_CREATE->value => self::CATEGORIES_CREATE->label(),
                self::CATEGORIES_EDIT->value => self::CATEGORIES_EDIT->label(),
                self::CATEGORIES_DELETE->value => self::CATEGORIES_DELETE->label(),
            ],
            'Ubicaciones' => [
                self::LOCATIONS_VIEW->value => self::LOCATIONS_VIEW->label(),
                self::LOCATIONS_CREATE->value => self::LOCATIONS_CREATE->label(),
                self::LOCATIONS_EDIT->value => self::LOCATIONS_EDIT->label(),
                self::LOCATIONS_DELETE->value => self::LOCATIONS_DELETE->label(),
            ],
            'Movimientos' => [
                self::INVENTORY_MOVEMENTS_VIEW->value => self::INVENTORY_MOVEMENTS_VIEW->label(),
                self::INVENTORY_MOVEMENTS_CREATE->value => self::INVENTORY_MOVEMENTS_CREATE->label(),
                self::INVENTORY_MOVEMENTS_EDIT->value => self::INVENTORY_MOVEMENTS_EDIT->label(),
                self::INVENTORY_MOVEMENTS_DELETE->value => self::INVENTORY_MOVEMENTS_DELETE->label(),
            ],
            'Mantenimiento' => [
                self::MAINTENANCE_RECORDS_VIEW->value => self::MAINTENANCE_RECORDS_VIEW->label(),
                self::MAINTENANCE_RECORDS_CREATE->value => self::MAINTENANCE_RECORDS_CREATE->label(),
                self::MAINTENANCE_RECORDS_EDIT->value => self::MAINTENANCE_RECORDS_EDIT->label(),
                self::MAINTENANCE_RECORDS_DELETE->value => self::MAINTENANCE_RECORDS_DELETE->label(),
            ],
            'Adjuntos' => [
                self::ATTACHMENTS_VIEW->value => self::ATTACHMENTS_VIEW->label(),
                self::ATTACHMENTS_CREATE->value => self::ATTACHMENTS_CREATE->label(),
                self::ATTACHMENTS_EDIT->value => self::ATTACHMENTS_EDIT->label(),
                self::ATTACHMENTS_DELETE->value => self::ATTACHMENTS_DELETE->label(),
            ],
            'Reportes' => [
                self::REPORTS_STOCK->value => self::REPORTS_STOCK->label(),
                self::REPORTS_MOVEMENTS->value => self::REPORTS_MOVEMENTS->label(),
                self::REPORTS_OUT_OF_SERVICE->value => self::REPORTS_OUT_OF_SERVICE->label(),
            ],
            'Configuración' => [
                self::SETTINGS_PROFILE->value => self::SETTINGS_PROFILE->label(),
                self::SETTINGS_PASSWORD->value => self::SETTINGS_PASSWORD->label(),
                self::SETTINGS_APPEARANCE->value => self::SETTINGS_APPEARANCE->label(),
            ],
        ];
    }

    /**
     * Get all permission values as an array.
     *
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(fn (self $permission) => $permission->value, self::cases());
    }
}
