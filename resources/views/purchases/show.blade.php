<x-app-layout>
    <x-breadcrumb :links="['Purcheases' => route('purchases.index'), 'Purchase Orders Details' => null]" />
    <div class="flex justify-between items-center py-3 border-b border-gray-200">
        <h2 class="my-4 text-3xl font-extrabold">Purchase Orders Details</h2>

        <button class="flex items-center gap-2 px-3 py-2 bg-indigo-600 rounded text-white font-semibold"
            onclick="printDiv('printArea')">Print <i class="fa-solid fa-print"></i></button>
    </div>

    <div id="printArea" class="bg-white rounded">
        <div>
            <div class="text-lg px-3 py-4 border-b">
                <div class="max-w-2xl flex justify-between mx-auto">
                    <p><strong>Supplier:</strong> {{ $purchase->supplier->name }}</p>
                    <p><strong>Order No:</strong> {{ $purchase->order_no }}</p>
                    <p><strong>Date:</strong> {{ $purchase->date }}</p>

                </div>
            </div>
            <div class="p-3">
                <table class="table table-bordered mt-3 w-full">
                    <thead>
                        <tr class="border border-b-2">
                            <th class="border py-1 px-2 text-start">S/L</th>
                            <th class="border py-1 px-2 text-start">Product</th>
                            <th class="border py-1 px-2 text-start">Unit Price</th>
                            <th class="border py-1 px-2 text-start">Quantity</th>
                            <th class="border py-1 px-2 text-start">Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchase->items as $item)
                            <tr>
                                <td class="border py-1 px-2">{{ $loop->iteration }}</td>
                                <td class="border py-1 px-2">{{ $item->product->name }}</td>
                                <td class="border py-1 px-2">{{ number_format($item->unit_price, 2) }}</td>
                                <td class="border py-1 px-2">{{ $item->quantity }}</td>
                                <td class="border py-1 px-2">{{ number_format($item->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="4" class="border py-1 px-2 text-end"><strong>Total:</strong></td>
                            <td class="border py-1 px-2 text-start">
                                <strong>{{ number_format($purchase->total_amount, 2) }}</strong>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4" class="border py-1 px-2 text-end"><strong>Payment:</strong></td>
                            <td class="border py-1 px-2 text-start">
                                <strong>{{ number_format($purchase->payment_amount, 2) }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="border py-1 px-2 text-end"><strong>Due:</strong></td>
                            <td class="border py-1 px-2 text-start">
                                <strong>{{ number_format($purchase->dueAmount, 2) }}</strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="pt-10 pb-6 gap-24 flex items-center px-3 justify-center text-lg font-bold">
                <p>Warehouse Created By</p>
                <p>Checked By</p>
            </div>
        </div>
    </div>

    <script>
        function printDiv(divId) {
            let content = document.getElementById(divId).innerHTML;
            let myWindow = window.open('', 'Print', 'height=600,width=800');
            myWindow.document.write('<html><head><title>Print</title>');
            myWindow.document.write(
                '<style>body{font-family: Arial,sans-serif;} table{width:100%;border-collapse:collapse;} th, td{border:1px solid #000;padding:5px;text-align:left;} th{background:#f0f0f0;}</style>'
            );
            myWindow.document.write('</head><body >');
            myWindow.document.write(content);
            myWindow.document.write('</body></html>');
            myWindow.document.close();
            myWindow.focus();
            myWindow.print();

        }
    </script>
</x-app-layout>
