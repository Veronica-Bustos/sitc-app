@props(['status', 'size' => 'md'])

@php
    $statusKey = strtolower($status);

    $styles = [
        // Item statuses
        'available' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'in_use' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'in_repair' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        'damaged' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        'lost' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        'retired' => 'bg-gray-700 text-gray-100 dark:bg-gray-900 dark:text-gray-400',
        // Boolean statuses
        'active' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'inactive' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        '1' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        '0' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
    ];

    $sizes = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-0.5 text-sm',
        'lg' => 'px-3 py-1 text-base',
    ];

    $statusLabels = [
        'available' => __('status.available'),
        'in_use' => __('status.in_use'),
        'in_repair' => __('status.in_repair'),
        'damaged' => __('status.damaged'),
        'lost' => __('status.lost'),
        'retired' => __('status.retired'),
        'active' => __('Active'),
        'inactive' => __('Inactive'),
        '1' => __('Active'),
        '0' => __('Inactive'),
    ];
@endphp

<span
    class="inline-flex items-center rounded-full font-medium {{ $styles[$statusKey] ?? $styles['available'] }} {{ $sizes[$size] }}">
    {{ $statusLabels[$statusKey] ?? $status }}
</span>
