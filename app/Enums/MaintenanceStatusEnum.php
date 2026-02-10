<?php

namespace App\Enums;

enum MaintenanceStatusEnum: string
{
    case PENDING = 'PENDING';
    case IN_PROGRESS = 'IN_PROGRESS';
    case COMPLETED = 'COMPLETED';
    case CANCELLED = 'CANCELLED';

    /**
     * Get all values as an array.
     *
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(fn (self $status) => $status->value, self::cases());
    }

    /**
     * Get a human readable label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => __('maintenance.pending'),
            self::IN_PROGRESS => __('maintenance.in_progress'),
            self::COMPLETED => __('maintenance.completed'),
            self::CANCELLED => __('maintenance.cancelled'),
        };
    }

    /**
     * Get the color class for the badge.
     */
    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            self::IN_PROGRESS => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            self::COMPLETED => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            self::CANCELLED => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        };
    }

    /**
     * Get the icon class for the status.
     */
    public function icon(): string
    {
        return match ($this) {
            self::PENDING => 'fas-clock',
            self::IN_PROGRESS => 'fas-spinner fa-spin',
            self::COMPLETED => 'fas-check-circle',
            self::CANCELLED => 'fas-times-circle',
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
            self::PENDING->value => self::PENDING->label(),
            self::IN_PROGRESS->value => self::IN_PROGRESS->label(),
            self::COMPLETED->value => self::COMPLETED->label(),
            self::CANCELLED->value => self::CANCELLED->label(),
        ];
    }
}
