<x-app-layout>
    <x-breadcrumb :links="['Purchases' => route('purchases.index'), 'Edit Purchase' => null]" />

    <div class="flex justify-between items-center py-3">
        <h2 class="my-4 text-3xl font-extrabold">Edit Purchase</h2>
    </div>

    <div>
        <form action="{{ route('purchases.update', $purchase->id) }}" method="POST"
            class="grid grid-cols-[2fr_1fr] gap-8">
            @csrf
            @method('PUT')

            {{-- Purchase Info & Products --}}
            <div class="bg-white border rounded">
                <div class="p-4 flex justify-between border-b">
                    <h3 class="text-xl font-bold">Purchase Information</h3>
                </div>

                <div class="mb-3 p-4">
                    <table class="table w-full border" id="productTable">
                        <thead class="font-bold">
                            <tr>
                                <th class="px-3 py-2 text-start">Product</th>
                                <th class="px-3 py-2 text-start">Qty</th>
                                <th class="px-3 py-2 text-start">Unit Price</th>
                                <th class="px-3 py-2 text-start">Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchase->items as $item)
                                <tr>
                                    <td class="px-3">
                                        <select name="product_id[]" class="rounded border-gray-300 py-1 w-full"
                                            required>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" name="quantity[]"
                                            class="qty rounded border-gray-300 py-1 w-full"
                                            value="{{ $item->quantity }}" min="1" required></td>
                                    <td><input type="number" name="unit_price[]"
                                            class="price rounded border-gray-300 py-1 w-full"
                                            value="{{ $item->unit_price }}" min="0" required></td>
                                    <td class="total px-3">{{ $item->total_price }}</td>
                                    <td class="flex items-center gap-2 text-sm">
                                        <button type="button"
                                            class="bg-red-600 py-1 px-2 rounded text-white delete-btn"
                                            onclick="removeRow(this)"> <i class="fa-regular fa-trash-can"></i></button>
                                        <button type="button" class="bg-indigo-700 py-1 px-2 rounded text-white"
                                            onclick="addRow()"><i class="fa-solid fa-plus"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="font-bold bg-gray-100 mt-6">
                            <tr class="mt-6">
                                <th>Grand Total</th>
                                <th id="grandQty" class="text-start px-3">0</th>
                                <th></th>
                                <th id="grandTotal" class="text-start px-3">0.00</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Other Info --}}
            <div class="bg-white border rounded">
                <div class="border-b p-4 flex justify-between">
                    <h3 class="text-xl font-bold">Other Information</h3>
                </div>

                <div class="p-4">
                    {{-- Supplier --}}
                    <fieldset class="mb-3 grid grid-cols-[150px_1fr] gap-6 items-center">
                        <label class="font-bold">Supplier: <span class="text-red-700">*</span></label>
                        <select name="supplier_id"
                            class="px-3 py-1 border border-gray-300 rounded w-full focus:ring-0 focus:outline-none focus:border-indigo-600"
                            required>
                            <option value="">-- Select Supplier --</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </fieldset>

                    {{-- Date --}}
                    <fieldset class="mb-3 grid grid-cols-[150px_1fr] gap-6 items-center">
                        <label class="font-bold">Date: <span class="text-red-700">*</span></label>
                        <input type="date" name="date" value="{{ $purchase->date }}"
                            class="px-3 py-1 border border-gray-300 rounded w-full focus:ring-0 focus:outline-none focus:border-indigo-600"
                            required>
                    </fieldset>

                    {{-- Status --}}
                    <fieldset class="mb-3 grid grid-cols-[150px_1fr] gap-6 items-center">
                        <label class="font-bold">Status: <span class="text-red-700">*</span></label>
                        <select name="status"
                            class="px-3 py-1 border border-gray-300 rounded w-full focus:ring-0 focus:outline-none focus:border-indigo-600"
                            required>
                            @foreach (['pending', 'completed', 'cancelled'] as $status)
                                <option value="{{ $status }}"
                                    {{ $purchase->status == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </fieldset>

                    {{-- Notes --}}
                    <fieldset class="mb-3 grid grid-cols-[150px_1fr] gap-6 items-start">
                        <label class="font-bold">Notes:</label>
                        <textarea name="notes"
                            class="px-3 py-1 border border-gray-300 rounded w-full focus:ring-0 focus:outline-none focus:border-indigo-600">{{ $purchase->notes }}</textarea>
                    </fieldset>

                    {{-- Actions --}}
                    <div class="flex justify-end gap-3 mt-4">
                        <a href="{{ route('purchases.index') }}"
                            class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded font-semibold transition-colors duration-200">Cancel</a>
                        <button type="submit"
                            class="px-3 py-1 bg-indigo-600 text-white rounded font-semibold hover:bg-indigo-500 transition-colors duration-200">Update
                            Purchase</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Scripts --}}
    <script>
        function calculateTotal(row) {
            let qty = parseFloat(row.querySelector(".qty").value) || 0;
            let price = parseFloat(row.querySelector(".price").value) || 0;
            row.querySelector(".total").textContent = (qty * price).toFixed(2);
            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            let rows = document.querySelectorAll("#productTable tbody tr");
            let grandQty = 0,
                grandTotal = 0;

            rows.forEach(row => {
                let qty = parseFloat(row.querySelector(".qty").value) || 0;
                let total = parseFloat(row.querySelector(".total").textContent) || 0;
                grandQty += qty;
                grandTotal += total;
            });

            document.getElementById("grandQty").textContent = grandQty;
            document.getElementById("grandTotal").textContent = grandTotal.toFixed(2);

            toggleDeleteButtons();
        }

        function toggleDeleteButtons() {
            let rows = document.querySelectorAll("#productTable tbody tr");
            rows.forEach(row => {
                let btn = row.querySelector(".delete-btn");
                if (rows.length === 1) {
                    btn.disabled = true;
                    btn.classList.add("opacity-50", "cursor-not-allowed");
                } else {
                    btn.disabled = false;
                    btn.classList.remove("opacity-50", "cursor-not-allowed");
                }
            });
        }

        document.addEventListener("input", function(e) {
            if (e.target.classList.contains("qty") || e.target.classList.contains("price")) {
                calculateTotal(e.target.closest("tr"));
            }
        });

        function addRow() {
            let tbody = document.querySelector("#productTable tbody");
            let row = tbody.querySelector("tr").cloneNode(true);
            row.querySelectorAll("input").forEach(input => input.value = input.classList.contains("qty") ? "1" : "0");
            row.querySelector(".total").textContent = "0";
            tbody.appendChild(row);
            calculateGrandTotal();
        }

        function removeRow(button) {
            let tbody = document.querySelector("#productTable tbody");
            if (tbody.querySelectorAll("tr").length > 1) {
                button.closest("tr").remove();
                calculateGrandTotal();
            }
        }

        // initialize totals on page load
        document.querySelectorAll("#productTable tbody tr").forEach(row => calculateTotal(row));
    </script>
</x-app-layout>
