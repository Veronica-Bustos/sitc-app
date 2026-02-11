<?php

namespace App\Enums;

enum ItemStatusEnum: string
{
    case AVAILABLE = 'available';
    case IN_USE = 'in_use';
    case IN_REPAIR = 'in_repair';
    case DAMAGED = 'damaged';
    case LOST = 'lost';
    case RETIRED = 'retired';

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
            self::AVAILABLE => __('status.available'),
            self::IN_USE => __('status.in_use'),
            self::IN_REPAIR => __('status.in_repair'),
            self::DAMAGED => __('status.damaged'),
            self::LOST => __('status.lost'),
            self::RETIRED => __('status.retired'),
        };
    }

    /**
     * Get the color class for the badge.
     */
    public function color(): string
    {
        return match ($this) {
            self::AVAILABLE => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            self::IN_USE => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            self::IN_REPAIR => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            self::DAMAGED => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            self::LOST => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            self::RETIRED => 'bg-black text-white',
        };
    }

    /**
     * Get the icon class for the status.
     */
    public function icon(): string
    {
        return match ($this) {
            self::AVAILABLE => 'fas-check-circle',
            self::IN_USE => 'fas-hand-holding',
            self::IN_REPAIR => 'fas-tools',
            self::DAMAGED => 'fas-exclamation-triangle',
            self::LOST => 'fas-question-circle',
            self::RETIRED => 'fas-archive',
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
            self::AVAILABLE->value => self::AVAILABLE->label(),
            self::IN_USE->value => self::IN_USE->label(),
            self::IN_REPAIR->value => self::IN_REPAIR->label(),
            self::DAMAGED->value => self::DAMAGED->label(),
            self::LOST->value => self::LOST->label(),
            self::RETIRED->value => self::RETIRED->label(),
        ];
    }

    /**
     * Get only out of service statuses.
     *
     * @return array<string, string>
     */
    public static function outOfServiceOptions(): array
    {
        return [
            self::IN_REPAIR->value => self::IN_REPAIR->label(),
            self::DAMAGED->value => self::DAMAGED->label(),
            self::LOST->value => self::LOST->label(),
            self::RETIRED->value => self::RETIRED->label(),
        ];
    }
}
