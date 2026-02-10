<?php

namespace App\Filters;

use App\Models\Location;
use Illuminate\Support\Facades\Log;

class LocationFilter extends QueryFilter
{
    protected array $allowedSorts = ['name', 'code', 'address', 'type', 'status', 'created_at', 'responsible'];

    protected string $defaultSort = '-created_at';

    public function type($value)
    {
        $this->builder->where('type', $value);

        return $this->builder;
    }

    public function status($value)
    {
        $this->builder->where('status', $value);

        return $this->builder;
    }

    public function responsible_user_id($value)
    {
        $this->builder->where('responsible_user_id', $value);

        return $this->builder;
    }

    public function search($value)
    {
        try {
            $ids = Location::search($value)->keys();

            if (empty($ids)) {
                $this->builder->whereRaw('0 = 1');

                return $this->builder;
            }

            $this->builder->whereIn('id', $ids);

            return $this->builder;
        } catch (\RuntimeException $e) {
            Log::error('Search service is unavailable: '.$e->getMessage());
            // Fallback to LIKE search
            $this->builder->where(function ($q) use ($value) {
                $q->where('locations.name', 'like', "%{$value}%")
                    ->orWhere('locations.code', 'like', "%{$value}%")
                    ->orWhere('locations.address', 'like', "%{$value}%");
            });

            return $this->builder;
        }
    }

    public function sortByResponsible($direction)
    {
        $this->builder->leftJoin('users', 'locations.responsible_user_id', '=', 'users.id')
            ->orderBy('users.name', $direction)
            ->select('locations.*');
    }
}
