@props([
    'type' => 'success', // default type
    'message' => '',
    'duration' => 3000, // milliseconds
])

@php
    $colors = [
        'success' => 'bg-green-500',
        'error' => 'bg-red-500',
        'warning' => 'bg-yellow-500',
        'info' => 'bg-blue-500',
    ];

    $bgColor = $colors[$type] ?? 'bg-gray-500';
@endphp

<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, {{ $duration }})"
    class="fixed top-5 right-5 {{ $bgColor }} text-white px-4 py-2 rounded shadow-lg">
    {{ $message }}
</div>
