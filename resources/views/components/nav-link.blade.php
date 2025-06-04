@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-[#1B56FD] text-sm font-medium leading-5 text-white focus:outline-none focus:border-[#1B56FD] transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-normal leading-5 text-white hover:text-[#3D90D7] hover:border-gray-300 focus:outline-none focus:text-[#3D90D7] focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
