<?php

namespace App\Filters;

use App\Models\InventoryMovement;
use Illuminate\Support\Facades\Log;

class MovementFilter extends QueryFilter
{
    protected array $allowedSorts = ['performed_at', 'created_at', 'item', 'movement_type'];

    protected string $defaultSort = '-performed_at';

    public function movementType($value)
    {
        $this->builder->where('movement_type', $value);

        return $this->builder;
    }

    public function item($value)
    {
        $this->builder->where('item_id', $value);

        return $this->builder;
    }

    public function fromLocation($value)
    {
        $this->builder->where('from_location_id', $value);

        return $this->builder;
    }

    public function toLocation($value)
    {
        $this->builder->where('to_location_id', $value);

        return $this->builder;
    }

    public function user($value)
    {
        $this->builder->where('user_id', $value);

        return $this->builder;
    }

    public function dateFrom($value)
    {
        $this->builder->whereDate('performed_at', '>=', $value);

        return $this->builder;
    }

    public function dateTo($value)
    {
        $this->builder->whereDate('performed_at', '<=', $value);

        return $this->builder;
    }

    public function search($value)
    {
        try {
            $ids = InventoryMovement::search($value)->keys();

            if (empty($ids)) {
                $this->builder->whereRaw('0 = 1');

                return $this->builder;
            }

            $this->builder->whereIn('id', $ids);

            return $this->builder;
        } catch (\RuntimeException $e) {
            Log::error('Search service is unavailable: '.$e->getMessage());
            Log::debug('Falling back to database search for movements with value: '.$value);

            $this->builder->where(function ($q) use ($value) {
                $q->where('reason', 'like', "%{$value}%")
                    ->orWhere('notes', 'like', "%{$value}%")
                    ->orWhere('reference_document', 'like', "%{$value}%");
            });

            return $this->builder;
        }
    }

    public function sortByItem($direction)
    {
        $this->builder->join('items', 'inventory_movements.item_id', '=', 'items.id')
            ->orderBy('items.name', $direction)
            ->select('inventory_movements.*');
    }
}
