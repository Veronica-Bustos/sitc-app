<?php

namespace App\Enums;

enum LocationTypeEnum: string
{
    case WAREHOUSE = 'WAREHOUSE';
    case SITE = 'SITE';
    case OFFICE = 'OFFICE';

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
            self::WAREHOUSE => 'Warehouse',
            self::SITE => 'Site',
            self::OFFICE => 'Office',
        };
    }
}
