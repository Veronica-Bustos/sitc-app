@props(['priority', 'size' => 'md'])

@php
    $styles = [
        'low' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'medium' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        'high' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
        'urgent' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
    ];

    $sizes = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-0.5 text-sm',
        'lg' => 'px-3 py-1 text-base',
    ];

    $priorityLabels = [
        'low' => __('priority.low'),
        'medium' => __('priority.medium'),
        'high' => __('priority.high'),
        'urgent' => __('priority.urgent'),
    ];
@endphp

<span
    class="inline-flex items-center rounded-full font-medium {{ $styles[$priority] ?? $styles['low'] }} {{ $sizes[$size] }}">
    {{ $priorityLabels[$priority] ?? $priority }}
</span>
