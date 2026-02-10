<?php

namespace App\Filters;

use App\Models\Category;
use Illuminate\Support\Facades\Log;

class CategoryFilter extends QueryFilter
{
    protected array $allowedSorts = ['name', 'slug', 'is_active', 'created_at', 'parent'];

    protected string $defaultSort = '-created_at';

    public function parent_id($value)
    {
        $this->builder->where('parent_id', $value);

        return $this->builder;
    }

    public function is_active($value)
    {
        $this->builder->where('is_active', $value);

        return $this->builder;
    }

    public function search($value)
    {
        try {
            $ids = Category::search($value)->keys();

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
                $q->where('categories.name', 'like', "%{$value}%")
                    ->orWhere('categories.slug', 'like', "%{$value}%");
            });

            return $this->builder;
        }
    }

    public function sortByParent($direction)
    {
        $this->builder->leftJoin('categories as parents', 'categories.parent_id', '=', 'parents.id')
            ->orderBy('parents.name', $direction)
            ->select('categories.*');
    }
}
