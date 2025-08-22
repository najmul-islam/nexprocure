<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        // Sorting
        $sortField = $request->get('sort', 'id');
        $sortOrder = $request->get('order', 'desc');
        $allowedFields = ['id', 'order_no', 'date', 'total_amount', 'status', 'created_at'];
        if (!in_array($sortField, $allowedFields)) {
            $sortField = 'id';
        }

        // Search
        $search = $request->get('search');

        // Supplier filter
        $supplierId = $request->get('supplier_id');

        // Date range filter
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Entries per page
        $entries = $request->get('entries', 10);

        // Query purchases with supplier relation
        $purchases = Purchase::with('supplier')
            ->when($search, function ($query, $search) {
                $query->where('order_no', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->when($supplierId, function ($query, $supplierId) {
                $query->where('supplier_id', $supplierId);
            })
            ->when($startDate, function ($query, $startDate) {
                $query->whereDate('date', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                $query->whereDate('date', '<=', $endDate);
            })
            ->orderBy($sortField, $sortOrder)
            ->paginate($entries)
            ->appends([
                'sort' => $sortField,
                'order' => $sortOrder,
                'search' => $search,
                'entries' => $entries,
                'supplier_id' => $supplierId,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);

        $totalAmount = Purchase::sum('total_amount');
        $totalPaid = Purchase::sum('paid_amount');
        $totalDue = Purchase::sum(DB::raw('total_amount - paid_amount'));
        $totalOrders = Purchase::count();

        $suppliers = Supplier::all();

        return view('purchases.index', compact(
            'purchases',
            'sortField',
            'sortOrder',
            'search',
            'entries',
            'totalAmount',
            'totalPaid',
            'totalDue',
            'totalOrders',
            'suppliers',
            'supplierId',
            'startDate',
            'endDate'
        ));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products  = Product::all();
        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'  => 'required|exists:suppliers,id',
            'date'         => 'required|date',
            'product_id.*' => 'required|exists:products,id',
            'quantity.*'   => 'required|integer|min:1',
            'unit_price.*' => 'required|numeric|min:0',
        ]);

        try {
            $purchase = Purchase::create([
                'supplier_id'  => $request->supplier_id,
                'order_no'     => 'PO-' . str_pad(Purchase::count() + 1, 4, '0', STR_PAD_LEFT),
                'date'         => $request->date,
                'notes'        => $request->notes,
                'status'       => 'pending',
                'total_amount' => 0,
            ]);

            $total = 0;

            foreach ($request->product_id as $key => $productId) {
                $qty  = $request->quantity[$key];
                $unit = $request->unit_price[$key];
                $totalPrice = $qty * $unit;

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id'  => $productId,
                    'unit'        => 'pcs',
                    'unit_price'  => $unit,
                    'quantity'    => $qty,
                    'total_price' => $totalPrice,
                ]);

                $total += $totalPrice;
            }

            $purchase->update(['total_amount' => $total]);

            return redirect()->route('purchases.index')->with('success', 'Purchase order created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create purchase order. Please try again.');
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load('supplier', 'items.product');
        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        $suppliers = Supplier::all();
        $products  = Product::all();
        $purchase->load('items');
        return view('purchases.edit', compact('purchase', 'suppliers', 'products'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        $request->validate([
            'supplier_id'  => 'required|exists:suppliers,id',
            'date'         => 'required|date',
            'status'       => 'required|in:pending,completed,cancelled',
            'product_id.*' => 'required|exists:products,id',
            'quantity.*'   => 'required|integer|min:1',
            'unit_price.*' => 'required|numeric|min:0',
        ]);

        try {
            $purchase->update([
                'supplier_id'  => $request->supplier_id,
                'date'         => $request->date,
                'notes'        => $request->notes,
                'status'       => $request->status,
                'total_amount' => 0,
            ]);

            $purchase->items()->delete();
            $total = 0;

            foreach ($request->product_id as $key => $productId) {
                $qty  = $request->quantity[$key];
                $unit = $request->unit_price[$key];
                $totalPrice = $qty * $unit;

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id'  => $productId,
                    'unit'        => 'pcs',
                    'unit_price'  => $unit,
                    'quantity'    => $qty,
                    'total_price' => $totalPrice,
                ]);

                $total += $totalPrice;
            }

            $purchase->update(['total_amount' => $total]);

            return redirect()->route('purchases.index')->with('success', 'Purchase order updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update purchase order. Please try again.');
        }
    }

    public function destroy(Purchase $purchase)
    {
        try {
            $purchase->delete();
            return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('purchases.index')->with('error', 'Failed to delete purchase. Please try again.');
        }
    }

    public function exportExcel()
    {
        $purchases = Purchase::with('supplier')->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headings
        $sheet->setCellValue('A1', 'Order No')
            ->setCellValue('B1', 'Date')
            ->setCellValue('C1', 'Supplier')
            ->setCellValue('D1', 'Total')
            ->setCellValue('E1', 'Price')
            ->setCellValue('F1', 'Paid')
            ->setCellValue('G1', 'Due')
            ->setCellValue('H1', 'Status')
            ->setCellValue('I1', 'Notes');

        $row = 2;
        foreach ($purchases as $p) {
            $due = $p->total_amount - $p->paid_amount;

            $sheet->setCellValue('A' . $row, $p->order_no)
                ->setCellValue('B' . $row, $p->date)
                ->setCellValue('C' . $row, $p->supplier->name ?? '-')
                ->setCellValue('D' . $row, $p->total_amount)
                ->setCellValue('E' . $row, $p->total_amount) // Price (same as total unless per item needed)
                ->setCellValue('F' . $row, $p->paid_amount)
                ->setCellValue('G' . $row, $due)
                ->setCellValue('H' . $row, ucfirst($p->status))
                ->setCellValue('I' . $row, $p->notes);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="purchases.xlsx"');
        $writer->save('php://output');
        exit;
    }

    public function exportCsv()
    {
        $purchases = Purchase::with('supplier')->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headings
        $sheet->setCellValue('A1', 'Order No')
            ->setCellValue('B1', 'Date')
            ->setCellValue('C1', 'Supplier')
            ->setCellValue('D1', 'Total')
            ->setCellValue('E1', 'Price')
            ->setCellValue('F1', 'Paid')
            ->setCellValue('G1', 'Due')
            ->setCellValue('H1', 'Status')
            ->setCellValue('I1', 'Notes');

        $row = 2;
        foreach ($purchases as $p) {
            $due = $p->total_amount - $p->paid_amount;

            $sheet->setCellValue('A' . $row, $p->order_no)
                ->setCellValue('B' . $row, $p->date)
                ->setCellValue('C' . $row, $p->supplier->name ?? '-')
                ->setCellValue('D' . $row, $p->total_amount)
                ->setCellValue('E' . $row, $p->total_amount)
                ->setCellValue('F' . $row, $p->paid_amount)
                ->setCellValue('G' . $row, $due)
                ->setCellValue('H' . $row, ucfirst($p->status))
                ->setCellValue('I' . $row, $p->notes);
            $row++;
        }

        $writer = new Csv($spreadsheet);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="purchases.csv"');
        $writer->save('php://output');
        exit;
    }

    public function exportTxt()
    {
        $purchases = Purchase::with('supplier')->get();
        $output = "Order No\tDate\tSupplier\tTotal\tPrice\tPaid\tDue\tStatus\tNotes\n";

        foreach ($purchases as $p) {
            $due = $p->total_amount - $p->paid_amount;
            $output .= "{$p->order_no}\t{$p->date}\t" .
                ($p->supplier->name ?? '-') . "\t" .
                "{$p->total_amount}\t{$p->total_amount}\t{$p->paid_amount}\t{$due}\t" .
                ucfirst($p->status) . "\t{$p->notes}\n";
        }

        return response($output)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="purchases.txt"');
    }

    public function exportPdf()
    {
        $purchases = Purchase::with('supplier')->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headings
        $sheet->setCellValue('A1', 'Order No')
            ->setCellValue('B1', 'Date')
            ->setCellValue('C1', 'Supplier')
            ->setCellValue('D1', 'Total')
            ->setCellValue('E1', 'Price')
            ->setCellValue('F1', 'Paid')
            ->setCellValue('G1', 'Due')
            ->setCellValue('H1', 'Status')
            ->setCellValue('I1', 'Notes');

        $row = 2;
        foreach ($purchases as $p) {
            $due = $p->total_amount - $p->paid_amount;

            $sheet->setCellValue('A' . $row, $p->order_no)
                ->setCellValue('B' . $row, $p->date)
                ->setCellValue('C' . $row, $p->supplier->name ?? '-')
                ->setCellValue('D' . $row, $p->total_amount)
                ->setCellValue('E' . $row, $p->total_amount)
                ->setCellValue('F' . $row, $p->paid_amount)
                ->setCellValue('G' . $row, $due)
                ->setCellValue('H' . $row, ucfirst($p->status))
                ->setCellValue('I' . $row, $p->notes);
            $row++;
        }

        \PhpOffice\PhpSpreadsheet\IOFactory::registerWriter('Pdf', Mpdf::class);
        $writer = new Mpdf($spreadsheet);

        if (ob_get_length() > 0) {
            ob_end_clean();
        }

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'purchases.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }
}