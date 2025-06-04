@props(['color' => 'gray'])

@php
    $colors = [
        'success' => 'bg-green-100 text-green-800 dark:bg-green-800/20 dark:text-green-400',
        'error' => 'bg-red-100 text-red-800 dark:bg-red-800/20 dark:text-red-400',
        'gray' => 'bg-gray-100 text-gray-800 dark:bg-gray-800/20 dark:text-gray-400',
    ];
@endphp

<span
    {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-md ' . ($colors[$color] ?? $colors['gray'])]) }}>
    {{ $slot }}
</span>
