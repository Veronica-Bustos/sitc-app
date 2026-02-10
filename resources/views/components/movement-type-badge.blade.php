@props(['type', 'size' => 'md'])

@php
    $styles = [
        'check_in' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'check_out' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        'transfer' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'return' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        'audit_adjustment' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        'disposal' => 'bg-gray-700 text-gray-100 dark:bg-gray-900 dark:text-gray-400',
    ];

    $sizes = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-0.5 text-sm',
        'lg' => 'px-3 py-1 text-base',
    ];

    $typeLabels = [
        'check_in' => __('movement.check_in'),
        'check_out' => __('movement.check_out'),
        'transfer' => __('movement.transfer'),
        'return' => __('movement.return'),
        'audit_adjustment' => __('movement.audit_adjustment'),
        'disposal' => __('movement.disposal'),
    ];

    $icons = [
        'check_in' => 'arrow-down',
        'check_out' => 'arrow-up',
        'transfer' => 'exchange-alt',
        'return' => 'undo',
        'audit_adjustment' => 'balance-scale',
        'disposal' => 'trash',
    ];
@endphp

<span
    class="inline-flex items-center gap-1 rounded-full font-medium {{ $styles[$type] ?? $styles['check_in'] }} {{ $sizes[$size] }}">
    <x-fas-{{ $icons[$type] ?? 'circle' }} class="w-auto h-auto" />
    {{ $typeLabels[$type] ?? $type }}
</span>
