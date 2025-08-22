@props(['links' => []])

<ul class="flex gap-1 items-center text-base">
    @foreach ($links as $label => $url)
        <li>
            @if ($url)
                <a href="{{ $url }}" class="font-semibold">{{ $label }}</a>
            @else
                <span class="text-gray-600">{{ $label }}</span>
            @endif
        </li>

        @if (!$loop->last)
            <li><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-chevron-right-icon lucide-chevron-right">
                    <path d="m9 18 6-6-6-6" />
                </svg></li>
        @endif
    @endforeach
</ul>
