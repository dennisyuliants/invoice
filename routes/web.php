<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CustomerController;

Route::get('/', [InvoiceController::class, 'dashboard'])->name('dashboard');

// Invoice Routes
Route::resource('invoices', InvoiceController::class);
Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
Route::get('invoices/{invoice}/export-pdf', [InvoiceController::class, 'exportPdf'])->name('invoices.export-pdf');
Route::get('invoices/export-excel', [InvoiceController::class, 'exportExcel'])->name('invoices.export-excel');

// Customer Routes
Route::resource('customers', CustomerController::class);
