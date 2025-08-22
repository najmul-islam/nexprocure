<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->get('sort', 'id');
        $sortOrder = $request->get('order', 'asc');

        $allowedFields = ['id', 'name', 'status', 'created_at'];
        if (!in_array($sortField, $allowedFields)) {
            $sortField = 'id';
        }

        // Search
        $search = $request->get('search');

        // Entries per page
        $entries = $request->get('entries', 10);

        // Query products
        $products = Product::when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })
            ->orderBy($sortField, $sortOrder)
            ->paginate($entries)
            ->appends([
                'sort' => $sortField,
                'order' => $sortOrder,
                'search' => $search,
                'entries' => $entries
            ]);

        return view('products.index', compact('products', 'sortField', 'sortOrder', 'search', 'entries'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            Product::create($request->all());
            return redirect()->route('products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create product. Please try again.');
        }
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $product->update($request->all());

            // Example warning if product is inactive
            if ($product->status === 'inactive') {
                return redirect()->route('products.edit', $product)
                    ->with('warning', 'Product updated but is currently inactive.');
            }

            return redirect()->route('products.edit', $product)
                ->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update product. Please try again.');
        }
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', 'Failed to delete product. Please try again.');
        }
    }

    public function exportExcel()
    {
        $products = Product::all(['id', 'name', 'status']);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'ID')
            ->setCellValue('B1', 'Name')
            ->setCellValue('C1', 'Status');

        $row = 2;
        foreach ($products as $product) {
            $sheet->setCellValue('A' . $row, $product->id)
                ->setCellValue('B' . $row, $product->name)
                ->setCellValue('C' . $row, ucfirst($product->status));
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="products.xlsx"');
        $writer->save('php://output');
        exit;
    }
    public function exportCsv()
    {
        $products = Product::all(['id', 'name', 'status']);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ID')
            ->setCellValue('B1', 'Name')
            ->setCellValue('C1', 'Status');

        $row = 2;
        foreach ($products as $product) {
            $sheet->setCellValue('A' . $row, $product->id)
                ->setCellValue('B' . $row, $product->name)
                ->setCellValue('C' . $row, ucfirst($product->status));
            $row++;
        }

        $writer = new Csv($spreadsheet);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="products.csv"');
        $writer->save('php://output');
        exit;
    }

    public function exportTxt()
    {
        $products = Product::all();
        $output = "ID\tName\tPrice\n";

        foreach ($products as $product) {
            $output .= "{$product->id}\t{$product->name}\t{$product->status}\n";
        }

        return response($output)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="products.txt"');
    }

    public function exportPdf()
    {
        $products = Product::all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Status');

        // Data
        $row = 2;
        foreach ($products as $product) {
            $sheet->setCellValue('A' . $row, $product->id);
            $sheet->setCellValue('B' . $row, $product->name);
            $sheet->setCellValue('C' . $row, $product->status);
            $row++;
        }

        // Set PDF renderer
        \PhpOffice\PhpSpreadsheet\IOFactory::registerWriter('Pdf', Mpdf::class);

        $writer = new Mpdf($spreadsheet);

        // Clean any output buffer to avoid corrupting the PDF
        if (ob_get_length() > 0) {
            ob_end_clean();
        }

        // Send response headers
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, 'products.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
