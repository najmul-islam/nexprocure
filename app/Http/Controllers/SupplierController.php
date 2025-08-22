<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        // Sorting
        $sortField = $request->get('sort', 'id');
        $sortOrder = $request->get('order', 'asc');

        $allowedFields = ['id', 'name', 'mobile', 'email', 'address', 'status', 'created_at'];
        if (!in_array($sortField, $allowedFields)) {
            $sortField = 'id';
        }

        // Search
        $search = $request->get('search');

        // Entries per page
        $entries = $request->get('entries', 10);

        // Query suppliers
        $suppliers = Supplier::when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('mobile', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%");
        })
            ->orderBy($sortField, $sortOrder)
            ->paginate($entries)
            ->appends([
                'sort' => $sortField,
                'order' => $sortOrder,
                'search' => $search,
                'entries' => $entries
            ]);

        return view('suppliers.index', compact('suppliers', 'sortField', 'sortOrder', 'search', 'entries'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'mobile' => 'required',
            'email'  => 'nullable|email|unique:suppliers',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            Supplier::create($request->all());
            return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create supplier. Please try again.');
        }
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'mobile' => 'required',
            'email'  => 'nullable|email|unique:suppliers,email,' . $supplier->id,
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $supplier->update($request->all());

            if ($supplier->status === 'inactive') {
                return redirect()->route('suppliers.edit', $supplier)
                    ->with('warning', 'Supplier updated but is currently inactive.');
            }

            return redirect()->route('suppliers.edit', $supplier)->with('success', 'Supplier updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update supplier. Please try again.');
        }
    }

    public function destroy(Supplier $supplier)
    {
        try {
            $supplier->delete();
            return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('suppliers.index')->with('error', 'Failed to delete supplier. Please try again.');
        }
    }


    public function exportExcel()
    {
        $suppliers = Supplier::all(['id', 'name', 'mobile', 'email', 'address', 'status']);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'ID')
            ->setCellValue('B1', 'Name')
            ->setCellValue('C1', 'Mobile')
            ->setCellValue('D1', 'Email')
            ->setCellValue('E1', 'Address')
            ->setCellValue('F1', 'Status');

        $row = 2;
        foreach ($suppliers as $supplier) {
            $sheet->setCellValue('A' . $row, $supplier->id)
                ->setCellValue('B' . $row, $supplier->name)
                ->setCellValue('C' . $row, $supplier->mobile)
                ->setCellValue('D' . $row, $supplier->email)
                ->setCellValue('E' . $row, $supplier->address)
                ->setCellValue('F' . $row, ucfirst($supplier->status));
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="suppliers.xlsx"');
        $writer->save('php://output');
        exit;
    }

    public function exportCsv()
    {
        $suppliers = Supplier::all(['id', 'name', 'mobile', 'email', 'address', 'status']);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'ID')
            ->setCellValue('B1', 'Name')
            ->setCellValue('C1', 'Mobile')
            ->setCellValue('D1', 'Email')
            ->setCellValue('E1', 'Address')
            ->setCellValue('F1', 'Status');

        $row = 2;
        foreach ($suppliers as $supplier) {
            $sheet->setCellValue('A' . $row, $supplier->id)
                ->setCellValue('B' . $row, $supplier->name)
                ->setCellValue('C' . $row, $supplier->mobile)
                ->setCellValue('D' . $row, $supplier->email)
                ->setCellValue('E' . $row, $supplier->address)
                ->setCellValue('F' . $row, ucfirst($supplier->status));
            $row++;
        }

        $writer = new Csv($spreadsheet);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="suppliers.csv"');
        $writer->save('php://output');
        exit;
    }

    public function exportTxt()
    {
        $suppliers = Supplier::all(['id', 'name', 'mobile', 'email', 'address', 'status']);
        $output = "ID\tName\tMobile\tEmail\tAddress\tStatus\n";

        foreach ($suppliers as $supplier) {
            $output .= "{$supplier->id}\t{$supplier->name}\t{$supplier->mobile}\t{$supplier->email}\t{$supplier->address}\t{$supplier->status}\n";
        }

        return response($output)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="suppliers.txt"');
    }

    public function exportPdf()
    {
        $suppliers = Supplier::all(['id', 'name', 'mobile', 'email', 'address', 'status']);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Mobile');
        $sheet->setCellValue('D1', 'Email');
        $sheet->setCellValue('E1', 'Address');
        $sheet->setCellValue('F1', 'Status');

        // Data
        $row = 2;
        foreach ($suppliers as $supplier) {
            $sheet->setCellValue('A' . $row, $supplier->id);
            $sheet->setCellValue('B' . $row, $supplier->name);
            $sheet->setCellValue('C' . $row, $supplier->mobile);
            $sheet->setCellValue('D' . $row, $supplier->email);
            $sheet->setCellValue('E' . $row, $supplier->address);
            $sheet->setCellValue('F' . $row, ucfirst($supplier->status));
            $row++;
        }

        // Register PDF renderer
        \PhpOffice\PhpSpreadsheet\IOFactory::registerWriter('Pdf', Mpdf::class);

        $writer = new Mpdf($spreadsheet);

        if (ob_get_length() > 0) {
            ob_end_clean();
        }

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'suppliers.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }
}