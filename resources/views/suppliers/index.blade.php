<x-app-layout>
    <x-breadcrumb :links="['Dashboard' => route('dashboard'), 'All Supplier' => null]" />

    <div class="flex justify-between items-center py-3 border-b border-gray-200">
        <h2 class="my-4 text-3xl font-extrabold">Suppliers</h2>
        <a href="{{ route('suppliers.create') }}"
            class="flex gap-1 text-nowrap shadow items-center bg-indigo-700 font-semibold text-white py-2 rounded px-3">
            Create Supplier
            <i class="fa-solid fa-plus text-sm"></i></a>
    </div>

    <div class="bg-white shadow rounded">
        <x-table-toolbar :exports="[
            'Copy' => route('suppliers.export.txt'),
            'Export to CSV' => route('suppliers.export.csv'),
            'Export to Excel' => route('suppliers.export.excel'),
            'Print' => route('suppliers.export.pdf'),
        ]" :current-entries="request('entries', 10)" :search-value="request('search', '')"
            index-route="{{ route('suppliers.index') }}" />

        <div class="p-4">
            <table class="table w-full">
                <thead>
                    <tr class="font-bold">
                        {{-- S/L Column --}}
                        <th class="border border-b-4 border-t-2 text-start py-2 px-3">
                            <a href="{{ route('suppliers.index', ['sort' => 'id', 'order' => $sortField == 'id' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}"
                                class="flex items-center justify-between">
                                S/L
                                <x-sort-icon field="id" :sortField="$sortField" />
                            </a>
                        </th>

                        {{-- Name Column --}}
                        <th class="border border-b-4 border-t-2 text-start py-2 px-3">
                            <a href="{{ route('suppliers.index', ['sort' => 'name', 'order' => $sortField == 'name' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}"
                                class="flex items-center justify-between">
                                Name
                                <x-sort-icon field="name" :sortField="$sortField" />
                            </a>
                        </th>

                        {{-- Mobile Column --}}
                        <th class="border border-b-4 border-t-2 text-start py-2 px-3">
                            <a href="{{ route('suppliers.index', ['sort' => 'mobile', 'order' => $sortField == 'mobile' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}"
                                class="flex items-center justify-between">
                                Mobile
                                <x-sort-icon field="mobile" :sortField="$sortField" />
                            </a>
                        </th>

                        {{-- Email Column --}}
                        <th class="border border-b-4 border-t-2 text-start py-2 px-3">
                            <a href="{{ route('suppliers.index', ['sort' => 'email', 'order' => $sortField == 'email' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}"
                                class="flex items-center justify-between">
                                Email
                                <x-sort-icon field="email" :sortField="$sortField" />
                            </a>
                        </th>

                        {{-- Address Column --}}
                        <th class="border border-b-4 border-t-2 text-start py-2 px-3">
                            <a href="{{ route('suppliers.index', ['sort' => 'address', 'order' => $sortField == 'address' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}"
                                class="flex items-center justify-between">
                                Address
                                <x-sort-icon field="address" :sortField="$sortField" />
                            </a>
                        </th>

                        {{-- Status Column --}}
                        <th class="border border-b-4 border-t-2 text-start py-2 px-3">
                            <a href="{{ route('suppliers.index', ['sort' => 'status', 'order' => $sortField == 'status' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}"
                                class="flex items-center justify-between">
                                Status
                                <x-sort-icon field="status" :sortField="$sortField" />
                            </a>
                        </th>

                        {{-- Actions Column --}}
                        <th class="border border-b-4 border-t-2 text-start py-2 px-3">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($suppliers as $supplier)
                        <tr>
                            <td class="border px-3 py-2">{{ $supplier->id }}</td>
                            <td class="border px-3 py-2">{{ $supplier->name }}</td>
                            <td class="border px-3 py-2">{{ $supplier->mobile }}</td>
                            <td class="border px-3 py-2">{{ $supplier->email }}</td>
                            <td class="border px-3 py-2">{{ $supplier->address }}</td>
                            <td class="border px-3 py-2">
                                <span
                                    class="badge {{ $supplier->status == 'active' ? 'bg-green-600' : 'bg-red-600' }} p-1 rounded text-white font-semibold text-sm">
                                    {{ ucfirst($supplier->status) }}
                                </span>
                            </td>
                            <td class="border px-3 py-2 flex items-center gap-2">
                                <a href="{{ route('suppliers.edit', $supplier->id) }}"
                                    class="bg-indigo-600 text-white px-2 py-1 rounded text-sm">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-600 px-2 py-1 text-white rounded text-sm"
                                        onclick="return confirm('Delete this supplier?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-2">No suppliers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>


            <x-pagination :paginator="$suppliers" />
        </div>

    </div>
</x-app-layout>
