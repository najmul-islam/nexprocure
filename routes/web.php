<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products Export
    Route::get('products/export/csv', [ProductController::class, 'exportCsv'])->name('products.export.csv');
    Route::get('products/export/excel', [ProductController::class, 'exportExcel'])->name('products.export.excel');
    Route::get('products/export/pdf', [ProductController::class, 'exportPdf'])->name('products.export.pdf');
    Route::get('products/export/txt', [ProductController::class, 'exportTxt'])->name('products.export.txt');

    // Suppliers
    Route::get('suppliers/export/csv', [SupplierController::class, 'exportCsv'])->name('suppliers.export.csv');
    Route::get('suppliers/export/excel', [SupplierController::class, 'exportExcel'])->name('suppliers.export.excel');
    Route::get('suppliers/export/txt', [SupplierController::class, 'exportTxt'])->name('suppliers.export.txt');
    Route::get('suppliers/export/pdf', [SupplierController::class, 'exportPdf'])->name('suppliers.export.pdf');

    // Purchases
    Route::get('purchases/export/csv', [PurchaseController::class, 'exportCsv'])->name('purchases.export.csv');
    Route::get('purchases/export/excel', [PurchaseController::class, 'exportExcel'])->name('purchases.export.excel');
    Route::get('purchases/export/txt', [PurchaseController::class, 'exportTxt'])->name('purchases.export.txt');
    Route::get('purchases/export/pdf', [PurchaseController::class, 'exportPdf'])->name('purchases.export.pdf');

    Route::resource('suppliers', SupplierController::class);
    Route::resource('products', ProductController::class);
    Route::resource('purchases', PurchaseController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';