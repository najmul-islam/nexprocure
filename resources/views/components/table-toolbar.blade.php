<div class="border-b p-4 flex justify-between">
    {{-- Export Buttons --}}
    <div class="flex gap-2 mb-4">
        @foreach ($exports as $label => $url)
            <a href="{{ $url }}"
                class="bg-indigo-600 text-white px-2 py-1 text-sm rounded font-semibold flex items-center gap-1">
                @php
                    $icons = [
                        'Copy' => 'fa-copy',
                        'Export to CSV' => 'fa-file-csv',
                        'Export to Excel' => 'fa-file-excel',
                        'Print' => 'fa-print',
                    ];
                @endphp
                <i class="fa-solid {{ $icons[$label] ?? 'fa-file' }}"></i> {{ ucfirst($label) }}
            </a>
        @endforeach
    </div>

    {{-- Entries and Search --}}
    <div class="flex gap-4 items-center mb-4">

        {{-- Entries Dropdown --}}
        <form action="{{ $indexRoute }}" method="GET" class="flex gap-2 items-center">
            <label for="entries" class="text-base font-semibold">Show</label>
            <select name="entries" id="entries"
                class="border border-gray-300 focus:ring-indigo-600 rounded px-2 py-1 w-16 text-base"
                onchange="this.form.submit()">
                @foreach ($entriesOptions as $option)
                    <option value="{{ $option }}" {{ $currentEntries == $option ? 'selected' : '' }}>
                        {{ $option }}</option>
                @endforeach
            </select>
        </form>

        {{-- Search Form --}}
        <form action="{{ $indexRoute }}" method="GET" class="flex gap-2 items-center">
            <fieldset class="relative">
                <label for="search" class="sr-only">Search</label>
                <input type="text" name="search" id="search" placeholder="Search..." value="{{ $searchValue }}"
                    class="border border-gray-300 focus:ring-indigo-600 rounded px-2 py-1 pr-8">

                @if ($searchValue)
                    <a href="{{ $indexRoute }}"
                        class="absolute right-1 top-1/2 transform -translate-y-1/2 bg-gray-100 text-black rounded-full p-1 hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.625" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-x-icon">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </a>
                @endif
            </fieldset>

            <button type="submit" class="bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-500">
                Search
            </button>
        </form>
    </div>
</div>
