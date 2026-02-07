<?php

namespace App\Enums;

enum RoleEnum: string
{
    case INACTIVO = 'inactivo';
    case ADMIN = 'admin';
    case ALMACENISTA = 'almacenista';
    case JEFE_OBRA = 'jefe_obra';
    case TECNICO = 'tecnico';
    case AUDITOR = 'auditor';

    /**
     * Get the display name for the role.
     */
    public function label(): string
    {
        return match ($this) {
            self::INACTIVO => 'Inactivo',
            self::ADMIN => 'Administrador',
            self::ALMACENISTA => 'Almacenista',
            self::JEFE_OBRA => 'Jefe de Obra',
            self::TECNICO => 'Técnico',
            self::AUDITOR => 'Auditor',
        };
    }

    /**
     * Get all roles except INACTIVO (for admin assignment).
     *
     * @return array<string, string>
     */
    public static function assignableRoles(): array
    {
        return [
            self::ADMIN->value => self::ADMIN->label(),
            self::ALMACENISTA->value => self::ALMACENISTA->label(),
            self::JEFE_OBRA->value => self::JEFE_OBRA->label(),
            self::TECNICO->value => self::TECNICO->label(),
            self::AUDITOR->value => self::AUDITOR->label(),
        ];
    }
}
