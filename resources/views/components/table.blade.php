<table class="table w-full">
    <thead>
        <tr class="font-bold">
            @foreach ($columns as $key => $label)
                <th class="border border-b-4 border-t-2 text-start py-2 px-3">
                    @if ($routePrefix)
                        <a href="{{ route($routePrefix . '.index', ['sort' => $key, 'order' => $sortField == $key && $sortOrder == 'asc' ? 'desc' : 'asc']) }}"
                            class="flex items-center justify-between">
                            {{ $label }}
                            <x-sort-icon field="{{ $key }}" :sortField="$sortField" />
                        </a>
                    @else
                        {{ $label }}
                    @endif
                </th>
            @endforeach
            @if (!empty($actions))
                <th class="border border-b-4 border-t-2 text-start py-2 px-3">Actions</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @forelse ($data as $row)
            <tr>
                @foreach ($columns as $key => $label)
                    <td class="border px-3 py-2">
                        @if ($key == 'status')
                            <span
                                class="badge {{ strtolower($row->$key) == 'active' ? 'bg-green-600' : 'bg-red-600' }} p-1 rounded text-white font-semibold text-sm">
                                {{ ucfirst($row->$key) }}
                            </span>
                        @else
                            {{ $row->$key }}
                        @endif
                    </td>
                @endforeach
                @if (!empty($actions))
                    <td class="border px-3 py-2 flex items-center gap-2">
                        @foreach ($actions as $action)
                            <a href="{{ route($action['route'], $row->id) }}" class="{{ $action['class'] }}">
                                {!! $action['icon'] !!}
                            </a>
                        @endforeach
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($columns) + (!empty($actions) ? 1 : 0) }}" class="border px-3 py-2 text-center">
                    No data found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
