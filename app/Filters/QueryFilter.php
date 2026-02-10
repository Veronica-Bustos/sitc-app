<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class QueryFilter
{
    protected Request $request;

    protected Builder $builder;

    protected array $allowedSorts = [];

    protected string $defaultSort = '';

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->request->all() as $name => $value) {
            if (! $this->hasFilter($name)) {
                continue;
            }

            if ($this->isEmpty($value)) {
                continue;
            }

            call_user_func_array([$this, $name], [$value]);
        }

        if (! $this->request->has('sort') && $this->defaultSort) {
            $this->sort($this->defaultSort);
        }

        return $this->builder;
    }

    public function sort(string $value)
    {
        $direction = 'asc';

        if (Str::startsWith($value, '-')) {
            $direction = 'desc';
            $value = Str::substr($value, 1);
        }

        if (! in_array($value, $this->allowedSorts)) {
            return;
        }

        $method = 'sortBy'.Str::studly($value);

        if (method_exists($this, $method)) {
            $this->$method($direction);

            return;
        }

        $this->builder->orderBy($value, $direction);
    }

    protected function hasFilter(string $name): bool
    {
        return method_exists($this, $name);
    }

    protected function isEmpty($value): bool
    {
        if (is_array($value)) {
            return empty(array_filter($value, fn ($v) => $v !== null && $v !== '\''));
        }

        return $value === '' || $value === null;
    }
}
