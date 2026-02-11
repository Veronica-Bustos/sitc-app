@props([
    'variant' => 'primary',
    'type' => null,
    'buttonType' => 'submit',
    'tag' => 'button',
    'size' => 'md',
])

@php
    // Support both 'variant' and 'type' props for backwards compatibility
    $variantStyle = $type ?? $variant;

    $sizeClasses = match ($size) {
        'sm' => 'py-1 px-3 text-sm',
        'md' => 'py-2 px-4 text-base',
        'lg' => 'py-3 px-6 text-lg',
        default => 'py-2 px-4 text-base',
    };

    $styleClasses = \Illuminate\Support\Arr::toCssClasses([
        'font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors flex items-center justify-center cursor-pointer',
        $sizeClasses,
        match ($variantStyle) {
            'primary' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 text-white',
            'danger' => 'bg-red-600 hover:bg-red-700 focus:ring-red-500 text-white',
            'outline'
                => 'border-2 border-gray-300 hover:bg-gray-50 text-gray-700 dark:border-gray-600 dark:hover:bg-gray-700 dark:text-gray-200',
            'secondary' => 'bg-gray-600 hover:bg-gray-700 focus:ring-gray-500 text-white',
            default => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 text-white',
        },
    ]);
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => $styleClasses]) }}>
    {{ $slot }}
    </{{ $tag }}>
