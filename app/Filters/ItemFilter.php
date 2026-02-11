<?php

namespace App\Filters;

use App\Models\Item;
use Illuminate\Support\Facades\Log;

class ItemFilter extends QueryFilter
{
    protected array $allowedSorts = ['name', 'code', 'status', 'created_at', 'category', 'location'];

    protected string $defaultSort = '-created_at';

    public function status($value)
    {
        $this->builder->where('status', $value);

        return $this->builder;
    }

    public function category($value)
    {
        $this->builder->where('category_id', $value);

        return $this->builder;
    }

    public function location($value)
    {
        $this->builder->where('current_location_id', $value);

        return $this->builder;
    }

    public function search($value)
    {
        Log::debug('Attempting to search items with value: ' . $value);
        try {
            // Use keys() when available to get IDs efficiently
            $ids = Item::search($value)->keys();

            if (empty($ids)) {
                $this->builder->whereRaw('0 = 1');

                return $this->builder;
            }

            $this->builder->whereIn('id', $ids);

            return $this->builder;
        } catch (\RuntimeException $e) {
            Log::error('Search service is unavailable: ' . $e->getMessage());
            Log::debug('Falling back to database search for items with value: ' . $value);
            // Fallback for DB driver without fulltext support — use LIKE
            $this->builder->where(function ($q) use ($value) {
                $q->where('items.code', 'like', "%{$value}%")
                    ->orWhere('items.name', 'like', "%{$value}%");
            });

            return $this->builder;
        }
    }

    public function sortByCategory($direction)
    {
        $this->builder->join('categories', 'items.category_id', '=', 'categories.id')
            ->orderBy('categories.name', $direction)
            ->select('items.*');
    }

    public function sortByLocation($direction)
    {
        $this->builder->join('locations', 'items.current_location_id', '=', 'locations.id')
            ->orderBy('locations.name', $direction)
            ->select('items.*');
    }
}
