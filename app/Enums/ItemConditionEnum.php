<?php

namespace App\Enums;

enum ItemConditionEnum: string
{
    case EXCELLENT = 'excellent';
    case GOOD = 'good';
    case FAIR = 'fair';
    case POOR = 'poor';

    /**
     * Get all values as an array.
     *
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(fn (self $condition) => $condition->value, self::cases());
    }

    /**
     * Get a human readable label for the condition.
     */
    public function label(): string
    {
        return match ($this) {
            self::EXCELLENT => __('condition.excellent'),
            self::GOOD => __('condition.good'),
            self::FAIR => __('condition.fair'),
            self::POOR => __('condition.poor'),
        };
    }

    /**
     * Get the color class for the badge.
     */
    public function color(): string
    {
        return match ($this) {
            self::EXCELLENT => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            self::GOOD => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            self::FAIR => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            self::POOR => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        };
    }

    /**
     * Get the icon class for the condition.
     */
    public function icon(): string
    {
        return match ($this) {
            self::EXCELLENT => 'fas-star',
            self::GOOD => 'fas-thumbs-up',
            self::FAIR => 'fas-minus-circle',
            self::POOR => 'fas-exclamation-circle',
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
            self::EXCELLENT->value => self::EXCELLENT->label(),
            self::GOOD->value => self::GOOD->label(),
            self::FAIR->value => self::FAIR->label(),
            self::POOR->value => self::POOR->label(),
        ];
    }
}
