<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return redirect()->route('admin.login');
})->name('home');

// Admin routes
Route::prefix('admin')->group(function () {
    // Login routes (no middleware)
    Route::get('/login', [AdminController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.post');
    
    // Protected admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
        
        // Booking management
        Route::get('/bookings', [AdminController::class, 'bookingsIndex'])->name('admin.bookings.index');
        Route::get('/bookings/create', [AdminController::class, 'bookingsCreate'])->name('admin.bookings.create');
        Route::post('/bookings', [AdminController::class, 'bookingsStore'])->name('admin.bookings.store');
        Route::get('/bookings/{id}', [AdminController::class, 'bookingsShow'])->name('admin.bookings.show');
        Route::put('/bookings/{id}/status', [AdminController::class, 'updateBookingStatus'])->name('admin.bookings.update-status');
        Route::delete('/bookings/{id}', [AdminController::class, 'deleteBooking'])->name('admin.bookings.delete');
        Route::get('/check-availability', [AdminController::class, 'checkAvailability'])->name('admin.availability.check');

        // Customer management
        Route::get('/customers', [AdminController::class, 'customersIndex'])->name('admin.customers.index');
        Route::get('/customers/{id}', [AdminController::class, 'customersShow'])->name('admin.customers.show');

        // Inventory management
        Route::get('/inventory', [AdminController::class, 'inventoryIndex'])->name('admin.inventory.index');
        Route::get('/inventory/create', [AdminController::class, 'inventoryCreate'])->name('admin.inventory.create');
        Route::get('/inventory/stock-in', [AdminController::class, 'inventoryStockInPage'])->name('admin.inventory.stock-in');
        Route::post('/inventory/stock-in', [AdminController::class, 'inventoryStockInStore'])->name('admin.inventory.stock-in.store');
        Route::get('/inventory/stock-out', [AdminController::class, 'inventoryStockOutPage'])->name('admin.inventory.stock-out');
        Route::post('/inventory/stock-out', [AdminController::class, 'inventoryStockOutStore'])->name('admin.inventory.stock-out.store');
        Route::post('/inventory', [AdminController::class, 'inventoryStore'])->name('admin.inventory.store');
        Route::get('/inventory/{id}', [AdminController::class, 'inventoryShow'])->name('admin.inventory.show');
        Route::get('/inventory/{id}/edit', [AdminController::class, 'inventoryEdit'])->name('admin.inventory.edit');
        Route::put('/inventory/{id}', [AdminController::class, 'inventoryUpdate'])->name('admin.inventory.update');
        Route::put('/inventory/{id}/status', [AdminController::class, 'inventoryUpdateStatus'])->name('admin.inventory.update-status');
        Route::post('/inventory/{id}/mark-damage', [AdminController::class, 'inventoryMarkDamage'])->name('admin.inventory.mark-damage');
        Route::post('/inventory/{id}/restore-damage', [AdminController::class, 'inventoryRestoreDamage'])->name('admin.inventory.restore-damage');
        Route::delete('/inventory/{id}', [AdminController::class, 'inventoryDelete'])->name('admin.inventory.delete');
        Route::post('/admin/inventory/{id}/add-stock', [AdminController::class, 'inventoryAddStock'])->name('admin.inventory.add-stock');
        Route::get('/inventory/{id}/stock-card', [AdminController::class, 'inventoryStockCard'])->name('admin.inventory.stock-card');
        
        //Return Item
        Route::post('/bookings/{id}/return', [AdminController::class, 'returnItems'])->name('admin.bookings.return');

        // Payment management
        Route::get('/payments', [PaymentController::class, 'index'])->name('admin.payments.index');
        Route::get('/bookings/{id}/payment/create', [PaymentController::class, 'create'])->name('admin.payments.create');
        Route::post('/bookings/{id}/payment', [PaymentController::class, 'store'])->name('admin.payments.store');
        Route::get('/payments/{id}', [PaymentController::class, 'show'])->name('admin.payments.show');
        
        // Sales reports
        Route::get('/reports/sales', [PaymentController::class, 'salesReport'])->name('admin.reports.sales');
    });
});
