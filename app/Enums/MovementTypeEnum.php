<?php

namespace App\Enums;

enum MovementTypeEnum: string
{
    case CHECK_IN = 'CHECK_IN';
    case CHECK_OUT = 'CHECK_OUT';
    case TRANSFER = 'TRANSFER';
    case RETURN = 'RETURN';
    case AUDIT_ADJUSTMENT = 'AUDIT_ADJUSTMENT';
    case DISPOSAL = 'DISPOSAL';

    /**
     * Get all values as an array.
     *
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(fn (self $type) => $type->value, self::cases());
    }

    /**
     * Get a human readable label for the type.
     */
    public function label(): string
    {
        return match ($this) {
            self::CHECK_IN => __('movement.check_in'),
            self::CHECK_OUT => __('movement.check_out'),
            self::TRANSFER => __('movement.transfer'),
            self::RETURN => __('movement.return'),
            self::AUDIT_ADJUSTMENT => __('movement.audit_adjustment'),
            self::DISPOSAL => __('movement.disposal'),
        };
    }

    /**
     * Get the color class for the badge.
     */
    public function color(): string
    {
        return match ($this) {
            self::CHECK_IN => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            self::CHECK_OUT => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            self::TRANSFER => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            self::RETURN => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            self::AUDIT_ADJUSTMENT => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            self::DISPOSAL => 'bg-gray-700 text-gray-100 dark:bg-gray-900 dark:text-gray-400',
        };
    }

    /**
     * Get the icon class for the type.
     */
    public function icon(): string
    {
        return match ($this) {
            self::CHECK_IN => 'fas-sign-in-alt',
            self::CHECK_OUT => 'fas-sign-out-alt',
            self::TRANSFER => 'fas-exchange-alt',
            self::RETURN => 'fas-undo',
            self::AUDIT_ADJUSTMENT => 'fas-clipboard-check',
            self::DISPOSAL => 'fas-trash-alt',
        };
    }

    /**
     * Get options for select dropdown.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::CHECK_IN->value => self::CHECK_IN->label(),
            self::CHECK_OUT->value => self::CHECK_OUT->label(),
            self::TRANSFER->value => self::TRANSFER->label(),
            self::RETURN->value => self::RETURN->label(),
            self::AUDIT_ADJUSTMENT->value => self::AUDIT_ADJUSTMENT->label(),
            self::DISPOSAL->value => self::DISPOSAL->label(),
        ];
    }
}
