<?php

namespace App\Enums;

enum MaintenancePriorityEnum: string
{
    case LOW = 'LOW';
    case MEDIUM = 'MEDIUM';
    case HIGH = 'HIGH';
    case URGENT = 'URGENT';

    /**
     * Get all values as an array.
     *
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(fn (self $priority) => $priority->value, self::cases());
    }

    /**
     * Get a human readable label for the priority.
     */
    public function label(): string
    {
        return match ($this) {
            self::LOW => __('priority.low'),
            self::MEDIUM => __('priority.medium'),
            self::HIGH => __('priority.high'),
            self::URGENT => __('priority.urgent'),
        };
    }

    /**
     * Get the color class for the badge.
     */
    public function color(): string
    {
        return match ($this) {
            self::LOW => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            self::MEDIUM => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            self::HIGH => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
            self::URGENT => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        };
    }

    /**
     * Get the icon class for the priority.
     */
    public function icon(): string
    {
        return match ($this) {
            self::LOW => 'fas-arrow-down',
            self::MEDIUM => 'fas-minus',
            self::HIGH => 'fas-arrow-up',
            self::URGENT => 'fas-exclamation-triangle',
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
            self::LOW->value => self::LOW->label(),
            self::MEDIUM->value => self::MEDIUM->label(),
            self::HIGH->value => self::HIGH->label(),
            self::URGENT->value => self::URGENT->label(),
        ];
    }
}
