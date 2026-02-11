<?php

namespace App\Filters;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserFilter extends QueryFilter
{
    protected array $allowedSorts = ['name', 'email', 'created_at'];

    protected string $defaultSort = '-created_at';

    public function role($value)
    {
        $this->builder->whereHas('roles', function ($query) use ($value) {
            $query->where('name', $value);
        });

        return $this->builder;
    }

    public function search($value)
    {
        try {
            $ids = User::search($value)->keys();

            if (empty($ids)) {
                $this->builder->whereRaw('0 = 1');

                return $this->builder;
            }

            $this->builder->whereIn('id', $ids);

            return $this->builder;
        } catch (\RuntimeException $e) {
            Log::error('Search service is unavailable: ' . $e->getMessage());
            Log::debug('Falling back to database search for users with value: ' . $value);

            $this->builder->where(function ($query) use ($value) {
                $query->where('users.name', 'like', "%{$value}%")
                    ->orWhere('users.email', 'like', "%{$value}%");
            });

            return $this->builder;
        }
    }
}
