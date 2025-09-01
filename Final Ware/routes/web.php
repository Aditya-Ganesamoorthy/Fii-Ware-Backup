<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\ReportController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\RoleAccessController;
Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Email Verification

        Route::resource('role_access', RoleAccessController::class)->except(['edit', 'update', 'show']);
    Route::get('/email/verify', fn () => view('auth.verify-email'))->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware(['throttle:6,1'])->name('verification.send');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Routes protected by dynamic role_page access
    Route::middleware('role_page')->group(function () {

        // General



        // Users and Roles

        Route::resource('role_access', RoleAccessController::class)->except(['edit', 'update', 'show']);
        Route::resource('users', UserController::class);
        Route::get('/users/{user}/delete', [UserController::class, 'confirmDelete'])->name('users.confirmDelete');
        Route::resource('roles', RoleController::class);
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');

        // Inventory & Products
        Route::resource('vendors', VendorController::class);

        Route::post('/vendors/check-gst', [VendorController::class, 'checkGst'])->name('vendors.check-gst');
        Route::post('/vendors/check-company', [VendorController::class, 'checkCompany'])->name('vendors.check-company');


        Route::resource('products', ProductController::class);
        Route::get('/products/{product}/delete', [ProductController::class, 'confirmDelete'])->name('products.confirmDelete');
        Route::resource('categories', CategoryController::class);

        // Purchases & Sales
        Route::resource('purchase', PurchaseController::class);

        Route::get('/product-search', [ProductController::class, 'search'])->name('product.search');
        Route::put('/purchase/update-group/{vendor}', [PurchaseController::class, 'updateGroup'])->name('purchase.updateGroup');
        Route::get('/purchase/{purchaseId}/delete-items', [PurchaseController::class, 'deleteItemsPage'])->name('purchase.deleteItems');
        Route::post('/purchase/{purchaseId}/delete-items', [PurchaseController::class, 'deleteSelectedItems'])->name('purchase.deleteItems.post');
        Route::resource('sales', SalesController::class);
        

        // Vehicle and Driver
        Route::resource('vehicles', VehicleController::class);
        Route::resource('drivers', DriverController::class);

       // Returns
        Route::get('/returns/get-products/{salesId}', [ReturnController::class, 'getProductsBySale'])->name('returns.getProducts');
        Route::get('/get-products/{salesId}', [ReturnController::class, 'getProducts']);
        Route::get('/returns', [ReturnController::class, 'index'])->name('returns.index');
        Route::get('/returns/create', [ReturnController::class, 'create'])->name('returns.create');
        Route::post('/returns', [ReturnController::class, 'store'])->name('returns.store');
        Route::resource('returns', ReturnController::class);
        Route::post('returns/{return}/accept', [ReturnController::class, 'accept'])->name('returns.accept');
        Route::post('returns/{return}/decline', [ReturnController::class, 'decline'])->name('returns.decline');
    });

// Report Routes
        Route::prefix('reports')->group(function () {
            Route::get('/stock', [ReportController::class, 'stockReport'])->name('reports.stock');
            Route::get('/stock/product/{product}', [ReportController::class, 'showProduct'])->name('reports.stock.product');
            Route::get('/stock/suggestions', [ReportController::class, 'stockSuggestions'])->name('reports.stock.suggestions');
            Route::get('/stock-by-vendor', [ReportController::class, 'stockByVendor'])->name('reports.stock-by-vendor');
            Route::get('/stock-by-product', [ReportController::class, 'stockByProduct'])->name('reports.stock-by-product');

            Route::get('/purchase', [ReportController::class, 'purchaseReport'])->name('reports.purchase');
            Route::get('/purchase/filter', [ReportController::class, 'filterPurchaseReport'])->name('reports.purchase.filter');
            Route::get('/purchase/export', [ReportController::class, 'exportPurchaseReport'])->name('reports.purchase.export');

            Route::get('/sales', [ReportController::class, 'salesReport'])->name('reports.sales');
            Route::get('/sales/filter', [ReportController::class, 'filterSalesReport'])->name('reports.sales.filter');
            Route::get('/sales/export', [ReportController::class, 'exportSalesReport'])->name('reports.sales.export');
        });

        // Returns (Driver role typically)


    // Return Routes
    
});

require __DIR__.'/auth.php';