<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class AttachmentFilter extends QueryFilter
{
    /**
     * Filter by search term (file_name, original_name, description)
     */
    public function search(string $search): Builder
    {
        return $this->builder->where(function ($query) use ($search) {
            $query->where('file_name', 'like', "%{$search}%")
                ->orWhere('original_name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Filter by MIME type category
     */
    public function mimeType(string $mimeType): Builder
    {
        return $this->builder->where('mime_type', 'like', "{$mimeType}%");
    }

    /**
     * Filter by attachable type (Item, InventoryMovement, MaintenanceRecord)
     */
    public function attachableType(string $type): Builder
    {
        $modelClass = match ($type) {
            'item' => \App\Models\Item::class,
            'movement' => \App\Models\InventoryMovement::class,
            'maintenance' => \App\Models\MaintenanceRecord::class,
            default => null,
        };

        if ($modelClass) {
            return $this->builder->where('attachable_type', $modelClass);
        }

        return $this->builder;
    }

    /**
     * Filter by uploader
     */
    public function uploader(int $uploaderId): Builder
    {
        return $this->builder->where('uploader_id', $uploaderId);
    }

    /**
     * Filter by featured status
     */
    public function featured(bool $featured): Builder
    {
        return $this->builder->where('is_featured', $featured);
    }

    /**
     * Filter by date range - from
     */
    public function dateFrom(string $date): Builder
    {
        return $this->builder->whereDate('created_at', '>=', $date);
    }

    /**
     * Filter by date range - to
     */
    public function dateTo(string $date): Builder
    {
        return $this->builder->whereDate('created_at', '<=', $date);
    }

    /**
     * Sort by column
     */
    public function sort(string $column, string $direction = 'asc'): Builder
    {
        $allowedColumns = [
            'file_name',
            'original_name',
            'size',
            'created_at',
            'order',
        ];

        if (in_array($column, $allowedColumns, true)) {
            return $this->builder->orderBy($column, $direction);
        }

        return $this->builder;
    }

    /**
     * Get available MIME type categories for filter
     */
    public static function getMimeTypeCategories(): array
    {
        return [
            'image/' => __('Images'),
            'application/pdf' => __('PDF Documents'),
            'application/msword' => __('Word Documents'),
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => __('Word Documents'),
            'application/vnd.ms-excel' => __('Excel Documents'),
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => __('Excel Documents'),
            'text/' => __('Text Files'),
            'video/' => __('Videos'),
            'audio/' => __('Audio'),
        ];
    }

    /**
     * Get available attachable types for filter
     */
    public static function getAttachableTypes(): array
    {
        return [
            'item' => __('Items'),
            'movement' => __('Movements'),
            'maintenance' => __('Maintenance Records'),
        ];
    }
}
