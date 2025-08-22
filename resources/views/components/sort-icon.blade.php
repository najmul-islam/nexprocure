@props(['field', 'sortField'])

<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
    stroke-width="2.625" stroke-linecap="round" stroke-linejoin="round"
    class="lucide lucide-arrow-down-up {{ $sortField === $field ? 'text-black' : 'text-gray-400' }}">
    <path d="m3 16 4 4 4-4" />
    <path d="M7 20V4" />
    <path d="m21 8-4-4-4 4" />
    <path d="M17 4v16" />
</svg>
