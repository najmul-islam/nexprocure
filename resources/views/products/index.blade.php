<x-app-layout>
    <x-breadcrumb :links="['Dashboard' => route('dashboard'), 'All Product' => null]" />

    <div class="flex justify-between items-center py-3 border-b border-gray-200">
        <h2 class="my-4 text-3xl font-extrabold">Products</h2>
        <a href="{{ route('products.create') }}"
            class="flex gap-1 text-nowrap shadow items-center bg-indigo-700 font-semibold text-white py-2 rounded px-3">
            Create Product
            <i class="fa-solid fa-plus text-sm"></i></a>
    </div>

    <div class="bg-white shadow rounded">
        <x-table-toolbar :exports="[
            'Copy' => route('products.export.txt'),
            'Export to CSV' => route('products.export.csv'),
            'Export to Excel' => route('products.export.excel'),
            'Print' => route('products.export.pdf'),
        ]" :current-entries="request('entries', 10)" :search-value="request('search', '')"
            index-route="{{ route('products.index') }}" />


        <div class="p-4">
            <table class="table w-full">
                <thead>
                    <tr class="font-bold">
                        {{-- S/L Column --}}
                        <th class="border border-b-4 border-t-2 text-start py-2 px-3">
                            <a href="{{ route('products.index', ['sort' => 'id', 'order' => $sortField == 'id' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}"
                                class="flex items-center justify-between ">
                                S/L

                                <x-sort-icon field="name" :sortField="$sortField" />
                            </a>
                        </th>

                        {{-- Name Column --}}
                        <th class="border border-b-4 border-t-2 text-start py-2 px-3">
                            <a href="{{ route('products.index', ['sort' => 'name', 'order' => $sortField == 'name' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}"
                                class="flex items-center justify-between ">
                                Name
                                <x-sort-icon field="name" :sortField="$sortField" />
                            </a>
                        </th>

                        {{-- Status Column --}}
                        <th class="border border-b-4 border-t-2 text-start py-2 px-3">
                            <a href="{{ route('products.index', ['sort' => 'status', 'order' => $sortField == 'status' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}"
                                class="flex items-center justify-between">
                                Status
                                <x-sort-icon field="name" :sortField="$sortField" />
                            </a>
                        </th>

                        <th class="border border-b-4 border-t-2 text-start py-2 px-3">Actions</th>
                    </tr>

                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td class="border px-3 py-2">{{ $product->id }}</td>
                            <td class="border px-3 py-2">{{ $product->name }}</td>
                            <td class="border px-3 py-2">
                                <span
                                    class="badge {{ $product->status == 'active' ? 'bg-green-600 p-1 rounded text-white font-semibold text-sm' : 'bg-red-600 p-1 rounded text-white font-semibold text-sm' }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                            <td class="border px-3 py-2 flex items-center gap-2">
                                <a href="{{ route('products.edit', $product->id) }}"
                                    class="bg-indigo-600 text-white px-2 py-1 rounded text-sm"><i
                                        class="fa-solid fa-pen-to-square"></i></a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button class="bg-red-600 px-2 py-1 text-white rounded text-sm"
                                        onclick="return confirm('Delete this product?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <x-pagination :paginator="$products" />
        </div>

    </div>

</x-app-layout>
