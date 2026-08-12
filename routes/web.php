<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\GoodsReceiptController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ProductController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // PR (Everyone can access their own PRs)
    Route::resource('pr', PurchaseRequestController::class);
    
    // PR Approvals (Only Managers & Admins)
    Route::middleware(['role:Manager'])->group(function () {
        Route::get('/approvals', [PurchaseRequestController::class, 'approvals'])->name('approvals.index');
        Route::post('/pr/{pr}/approve', [PurchaseRequestController::class, 'approve'])->name('pr.approve');
        Route::post('/pr/{pr}/reject', [PurchaseRequestController::class, 'reject'])->name('pr.reject');
    });
    
    // PO (Procurement & Admins)
    Route::middleware(['role:Procurement'])->group(function () {
        Route::resource('po', PurchaseOrderController::class);
    });
    // Let everyone view POs, but only Procurement can create/edit them. 
    // To handle this, we'll allow index/show for all, and restrict create/store/edit/update/destroy in the controller or via routes.
    // However, for simplicity in portfolio, let's just make the whole module restricted to Procurement/Manager/Admin, 
    // but PR creators can view POs tied to their PRs via the PR page.
    
    // GR (Warehouse & Admins)
    Route::middleware(['role:Warehouse'])->group(function () {
        Route::resource('gr', GoodsReceiptController::class);
    });
    
    // Invoices & Payments (Finance & Admins)
    Route::middleware(['role:Finance'])->group(function () {
        Route::resource('invoices', InvoiceController::class);
        Route::post('/invoices/{invoice}/verify', [InvoiceController::class, 'verify'])->name('invoices.verify');
        Route::resource('payments', PaymentController::class);
    });

    // Master Data (Procurement, Finance, Admins)
    Route::middleware(['role:Procurement,Finance'])->group(function () {
        Route::resource('vendors', VendorController::class);
        Route::resource('products', ProductController::class);
    });
    
    // System (Admins)
    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/reports', function() { return view('reports.index'); })->name('reports.index');
        Route::get('/audit', function() { return view('audit.index'); })->name('audit.index');
    });
});
