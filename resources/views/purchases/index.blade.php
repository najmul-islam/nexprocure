<x-app-layout>
    <x-breadcrumb :links="['Dashboard' => route('dashboard'), 'Purchase Orders' => null]" />

    <div class="flex justify-between items-center py-3 border-b border-gray-200">
        <h2 class="my-4 text-3xl font-extrabold">Purchase Orders</h2>
        <a href="{{ route('purchases.create') }}"
            class="flex gap-1 text-nowrap shadow items-center bg-indigo-700 font-semibold text-white py-2 rounded px-3">
            Create Purchase
            <i class="fa-solid fa-plus text-sm"></i>
        </a>
    </div>

    <div class="bg-white shadow rounded">
        <form action="{{ route('purchases.index') }}" method="GET"
            class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end px-4 py-3">

            {{-- Supplier --}}
            <fieldset class="flex flex-col">
                <label for="supplier" class="font-semibold mb-1">Supplier <span
                        class="text-xl text-red-500">*</span></label>
                <select name="supplier_id" id="supplier"
                    class="px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-0 focus:border-indigo-600"
                    required>
                    <option value="">-- All Suppliers --</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}"
                            {{ isset($supplierId) && $supplierId == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </fieldset>

            {{-- Start Date --}}
            <fieldset class="flex flex-col">
                <label for="startDate" class="font-semibold mb-1">Start Date <span class="text-red-500">*</span></label>
                <input type="date" name="start_date" id="startDate" value="{{ $startDate ?? '' }}"
                    class="px-3 py-1 border border-gray-300 rounded focus:outline-none focus:ring-0 focus:border-indigo-600"
                    required>
            </fieldset>

            {{-- End Date --}}
            <fieldset class="flex flex-col">
                <label for="endDate" class="font-semibold mb-1">End Date <span
                        class="text-xl text-red-500">*</span></label>
                <input type="date" name="end_date" id="endDate" value="{{ $endDate ?? '' }}"
                    class="px-3 py-1 border border-gray-300 rounded focus:outline-none focus:ring-0 focus:border-indigo-600"
                    required>
            </fieldset>

            {{-- Search Button --}}
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-[6px] bg-indigo-600 text-white rounded hover:bg-indigo-500 font-semibold">
                    Search
                </button>
                {{-- Clear Button --}}
                <a href="{{ route('purchases.index') }}"
                    class="px-4 py-[6px] bg-gray-400 text-white rounded hover:bg-gray-500 font-semibold">
                    Clear
                </a>
            </div>
        </form>

        <x-table-toolbar :exports="[
            'Copy' => route('purchases.export.txt'),
            'Export to CSV' => route('purchases.export.csv'),
            'Export to Excel' => route('purchases.export.excel'),
            'Print' => route('purchases.export.pdf'),
        ]" :current-entries="request('entries', 10)" :search-value="request('search', '')"
            index-route="{{ route('purchases.index') }}" />

        <div class="p-4">
            <table class="table w-full">
                <thead>
                    <tr class="font-bold">
                        {{-- Order No --}}
                        <th class="border border-b-4 border-t-2 text-start py-2 px-3">
                            <a href="{{ route('purchases.index', ['sort' => 'order_no', 'order' => $sortField == 'order_no' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}"
                                class="flex items-center justify-between">
                                Order No
                                <x-sort-icon field="order_no" :sortField="$sortField" />
                            </a>
                        </th>
                        {{-- Date --}}
                        <th class="border border-b-4 border-t-2 text-start py-2 px-3">
                            <a href="{{ route('purchases.index', ['sort' => 'date', 'order' => $sortField == 'date' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}"
                                class="flex items-center justify-between">
                                Date
                                <x-sort-icon field="date" :sortField="$sortField" />
                            </a>
                        </th>
                        {{-- Supplier --}}
                        <th class="border border-b-4 border-t-2 text-start py-2 px-3">
                            <a href="{{ route('purchases.index', ['sort' => 'supplier_id', 'order' => $sortField == 'supplier_id' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}"
                                class="flex items-center justify-between">
                                Supplier
                                <x-sort-icon field="supplier_id" :sortField="$sortField" />
                            </a>
                        </th>



                        {{-- Total --}}
                        <th class="border border-b-4 border-t-2 text-start py-2 px-3">
                            <a href="{{ route('purchases.index', ['sort' => 'total_amount', 'order' => $sortField == 'total_amount' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}"
                                class="flex items-center justify-between">
                                Total
                                <x-sort-icon field="total_amount" :sortField="$sortField" />
                            </a>
                        </th>

                        {{-- Paid --}}
                        <th class="border border-b-4 border-t-2 text-start py-2 px-3">
                            <a href="{{ route('purchases.index', ['sort' => 'paid_amount', 'order' => $sortField == 'paid_amount' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}"
                                class="flex items-center justify-between">
                                Paid
                                <x-sort-icon field="paid_amount" :sortField="$sortField" />
                            </a>
                        </th>

                        {{-- Due --}}
                        <th class="border border-b-4 border-t-2 text-start py-2 px-3">
                            <a href="{{ route('purchases.index', ['sort' => 'due_amount', 'order' => $sortField == 'due_amount' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}"
                                class="flex items-center justify-between">
                                Due
                                <x-sort-icon field="due_amount" :sortField="$sortField" />
                            </a>
                        </th>

                        {{-- Status --}}
                        <th class="border border-b-4 border-t-2 text-start py-2 px-3">
                            <a href="{{ route('purchases.index', ['sort' => 'status', 'order' => $sortField == 'status' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}"
                                class="flex items-center justify-between">
                                Status
                                <x-sort-icon field="status" :sortField="$sortField" />
                            </a>
                        </th>

                        <th class="border border-b-4 border-t-2 text-start py-2 px-3">
                            <a href="{{ route('purchases.index', ['sort' => 'notes', 'order' => $sortField == 'notes' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}"
                                class="flex items-center justify-between">
                                Notes
                                <x-sort-icon field="status" :sortField="$sortField" />
                            </a>
                        </th>

                        {{-- Actions --}}
                        <th class="border border-b-4 border-t-2 text-start py-2 px-3">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($purchases as $purchase)
                        <tr>
                            <td class="border px-3 py-2">{{ $purchase->order_no }}</td>
                            <td class="border px-3 py-2">{{ $purchase->date }}</td>
                            <td class="border px-3 py-2">{{ $purchase->supplier->name }}</td>
                            <td class="border px-3 py-2">{{ number_format($purchase->total_amount, 2) }}</td>
                            <td class="border px-3 py-2">{{ number_format($purchase->paid_amount, 2) }}</td>
                            <td class="border px-3 py-2">{{ number_format($purchase->due_amount, 2) }}</td>
                            <td class="border px-3 py-2">
                                <span
                                    class="badge p-1 rounded text-white font-semibold text-sm
        {{ $purchase->status == 'completed' ? 'bg-green-600' : ($purchase->status == 'pending' ? 'bg-yellow-600' : 'bg-red-600') }}">
                                    {{ ucfirst($purchase->status) }}
                                </span>
                            </td>
                            <td class="border px-3 py-2">{{ $purchase->notes }}</td>
                            <td class="border px-3 py-2 flex items-center gap-2">
                                <a href="{{ route('purchases.show', $purchase->id) }}"
                                    class="bg-green-600 text-white px-2 py-1 rounded text-sm">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('purchases.edit', $purchase->id) }}"
                                    class="bg-indigo-600 text-white px-2 py-1 rounded text-sm">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-600 px-2 py-1 text-white rounded text-sm"
                                        onclick="return confirm('Delete this purchase?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-2">No purchase orders found.</td>
                        </tr>
                    @endforelse

                    {{-- Summary Row for Entire Database --}}
                    <tr class="font-bold bg-gray-100">
                        <td colspan="3" class="border px-3 py-2 text-center">Totals (All Records):</td>
                        <td class="border px-3 py-2">{{ number_format($totalAmount, 2) }}</td>
                        <td class="border px-3 py-2">{{ number_format($totalPaid, 2) }}</td>
                        <td class="border px-3 py-2">{{ number_format($totalDue, 2) }}</td>
                        <td colspan="3" class="border px-3 py-2"></td>
                    </tr>
                </tbody>
            </table>

            <x-pagination :paginator="$purchases" />
        </div>
    </div>
</x-app-layout>
