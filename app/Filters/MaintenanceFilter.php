<?php

namespace App\Filters;

use App\Models\MaintenanceRecord;
use Illuminate\Support\Facades\Log;

class MaintenanceFilter extends QueryFilter
{
    protected array $allowedSorts = ['request_date', 'intervention_date', 'completion_date', 'status', 'priority', 'created_at', 'item'];

    protected string $defaultSort = '-request_date';

    public function status($value)
    {
        $this->builder->where('status', $value);

        return $this->builder;
    }

    public function type($value)
    {
        $this->builder->where('type', $value);

        return $this->builder;
    }

    public function priority($value)
    {
        $this->builder->where('priority', $value);

        return $this->builder;
    }

    public function item($value)
    {
        $this->builder->where('item_id', $value);

        return $this->builder;
    }

    public function technician($value)
    {
        $this->builder->where('technician_id', $value);

        return $this->builder;
    }

    public function dateFrom($value)
    {
        $this->builder->whereDate('request_date', '>=', $value);

        return $this->builder;
    }

    public function dateTo($value)
    {
        $this->builder->whereDate('request_date', '<=', $value);

        return $this->builder;
    }

    public function search($value)
    {
        try {
            $ids = MaintenanceRecord::search($value)->keys();

            if (empty($ids)) {
                $this->builder->whereRaw('0 = 1');

                return $this->builder;
            }

            $this->builder->whereIn('id', $ids);

            return $this->builder;
        } catch (\RuntimeException $e) {
            Log::error('Search service is unavailable: '.$e->getMessage());
            Log::debug('Falling back to database search for maintenance records with value: '.$value);

            $this->builder->where(function ($q) use ($value) {
                $q->where('description', 'like', "%{$value}%")
                    ->orWhere('diagnosis', 'like', "%{$value}%")
                    ->orWhere('actions_taken', 'like', "%{$value}%");
            });

            return $this->builder;
        }
    }

    public function sortByItem($direction)
    {
        $this->builder->join('items', 'maintenance_records.item_id', '=', 'items.id')
            ->orderBy('items.name', $direction)
            ->select('maintenance_records.*');
    }
}
