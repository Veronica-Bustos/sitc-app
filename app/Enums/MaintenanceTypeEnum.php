<?php

namespace App\Enums;

enum MaintenanceTypeEnum: string
{
    case PREVENTIVE = 'PREVENTIVE';
    case CORRECTIVE = 'CORRECTIVE';
    case CALIBRATION = 'CALIBRATION';
    case INSPECTION = 'INSPECTION';

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
            self::PREVENTIVE => __('maintenance.type.preventive'),
            self::CORRECTIVE => __('maintenance.type.corrective'),
            self::CALIBRATION => __('maintenance.type.calibration'),
            self::INSPECTION => __('maintenance.type.inspection'),
        };
    }

    /**
     * Get the icon class for the type.
     */
    public function icon(): string
    {
        return match ($this) {
            self::PREVENTIVE => 'fas-shield-alt',
            self::CORRECTIVE => 'fas-wrench',
            self::CALIBRATION => 'fas-sliders-h',
            self::INSPECTION => 'fas-search',
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
            self::PREVENTIVE->value => self::PREVENTIVE->label(),
            self::CORRECTIVE->value => self::CORRECTIVE->label(),
            self::CALIBRATION->value => self::CALIBRATION->label(),
            self::INSPECTION->value => self::INSPECTION->label(),
        ];
    }
}
