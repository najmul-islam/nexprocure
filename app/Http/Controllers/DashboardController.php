<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get counts from the database
        $totalProducts = Product::count();
        $totalPurchases = Purchase::count();
        $totalSuppliers = Supplier::count();

        // Pass counts to the view
        return view('dashboard', compact('totalProducts', 'totalPurchases', 'totalSuppliers'));
    }
}