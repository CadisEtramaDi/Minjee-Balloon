<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RevenueController;

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
        Route::post('/inventory', [AdminController::class, 'inventoryStore'])->name('admin.inventory.store');
        Route::get('/inventory/{id}', [AdminController::class, 'inventoryShow'])->name('admin.inventory.show');
        Route::get('/inventory/{id}/edit', [AdminController::class, 'inventoryEdit'])->name('admin.inventory.edit');
        Route::put('/inventory/{id}', [AdminController::class, 'inventoryUpdate'])->name('admin.inventory.update');
        Route::put('/inventory/{id}/status', [AdminController::class, 'inventoryUpdateStatus'])->name('admin.inventory.update-status');
        Route::post('/inventory/{id}/mark-damage', [AdminController::class, 'inventoryMarkDamage'])->name('admin.inventory.mark-damage');
        Route::post('/inventory/{id}/restore-damage', [AdminController::class, 'inventoryRestoreDamage'])->name('admin.inventory.restore-damage');
        Route::delete('/inventory/{id}', [AdminController::class, 'inventoryDelete'])->name('admin.inventory.delete');
        Route::post('/admin/inventory/{id}/add-stock', [AdminController::class, 'inventoryAddStock'])->name('admin.inventory.add-stock');
        
        //Return Item
        Route::post('/bookings/{id}/return', [AdminController::class, 'returnItems'])->name('admin.bookings.return');

        // Payment management
        Route::get('/payments', [PaymentController::class, 'index'])->name('admin.payments.index');
        Route::get('/bookings/{id}/payment/create', [PaymentController::class, 'create'])->name('admin.payments.create');
        Route::post('/bookings/{id}/payment', [PaymentController::class, 'store'])->name('admin.payments.store');
        Route::get('/payments/{id}', [PaymentController::class, 'show'])->name('admin.payments.show');
        
        // Revenue management
        Route::get('/revenue', [RevenueController::class, 'index'])->name('admin.revenue.index');
        Route::get('/revenue/{id}', [RevenueController::class, 'show'])->name('admin.revenue.show');
        Route::post('/revenue/generate-daily', [RevenueController::class, 'generateDaily'])->name('admin.revenue.generate-daily');
        Route::post('/revenue/generate-monthly', [RevenueController::class, 'generateMonthly'])->name('admin.revenue.generate-monthly');
        Route::post('/revenue/auto-aggregate', [RevenueController::class, 'autoAggregate'])->name('admin.revenue.auto-aggregate');
        
        // Sales reports
        Route::get('/reports/sales', [PaymentController::class, 'salesReport'])->name('admin.reports.sales');
    });
});
