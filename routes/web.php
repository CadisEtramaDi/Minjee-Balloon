<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RentalController;

Route::get('/', function () {
    return redirect()->route('admin.login');
})->name('home');

Route::prefix('admin')->group(function () {

    // ── Auth ──────────────────────────────────────────────────────────────
    Route::get('/login', [AdminController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.post');

    // ── Protected Routes ──────────────────────────────────────────────────
    Route::middleware('admin')->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

        // ── Bookings (BookingController handles everything) ────────────────
        Route::get('/bookings', [BookingController::class, 'index'])->name('admin.bookings.index');
        Route::get('/bookings/create', [BookingController::class, 'create'])->name('admin.bookings.create');
        Route::post('/bookings', [BookingController::class, 'storeWeb'])->name('admin.bookings.store');
        Route::get('/bookings/{id}', [BookingController::class, 'showWeb'])->name('admin.bookings.show');
        Route::get('/bookings/{id}/edit', [BookingController::class, 'edit'])->name('admin.bookings.edit');
        Route::delete('/bookings/{id}', [BookingController::class, 'deleteWeb'])->name('admin.bookings.delete');
        Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancelWeb'])->name('admin.bookings.cancel');
        Route::put('/bookings/{id}/status', [BookingController::class, 'updateStatusWeb'])->name('admin.bookings.update-status');
        Route::post('/bookings/{id}/payment', [BookingController::class, 'recordPaymentWeb'])->name('admin.bookings.payment');
        Route::post('/bookings/{id}/start-rental', [BookingController::class, 'startRentalWeb'])->name('admin.bookings.start-rental');
        Route::post('/bookings/{id}/process-return', [BookingController::class, 'processReturnWeb'])->name('admin.bookings.process-return');
        Route::get('/availability', [BookingController::class, 'availabilityPage'])->name('admin.availability.page');
        Route::get('/check-availability', [BookingController::class, 'checkAvailability'])->name('admin.availability.check');

        // ── Customers (Web) ───────────────────────────────────────────────
        Route::get('/customers', [AdminController::class, 'customersIndex'])->name('admin.customers.index');
        Route::get('/customers/{id}', [AdminController::class, 'customersShow'])->name('admin.customers.show');

        // ── Inventory (Web) ───────────────────────────────────────────────
        Route::get('/inventory', [AdminController::class, 'inventoryIndex'])->name('admin.inventory.index');
        Route::get('/inventory/create', [AdminController::class, 'inventoryCreate'])->name('admin.inventory.create');
        Route::post('/inventory', [AdminController::class, 'inventoryStore'])->name('admin.inventory.store');
        Route::get('/inventory/stock-in', [AdminController::class, 'inventoryStockInPage'])->name('admin.inventory.stock-in');
        Route::post('/inventory/stock-in', [AdminController::class, 'inventoryStockInStore'])->name('admin.inventory.stock-in.store');
        Route::get('/inventory/stock-out', [AdminController::class, 'inventoryStockOutPage'])->name('admin.inventory.stock-out');
        Route::post('/inventory/stock-out', [AdminController::class, 'inventoryStockOutStore'])->name('admin.inventory.stock-out.store');
        Route::get('/inventory/{id}', [AdminController::class, 'inventoryShow'])->name('admin.inventory.show');
        Route::get('/inventory/{id}/edit', [AdminController::class, 'inventoryEdit'])->name('admin.inventory.edit');
        Route::put('/inventory/{id}', [AdminController::class, 'inventoryUpdate'])->name('admin.inventory.update');
        Route::put('/inventory/{id}/status', [AdminController::class, 'inventoryUpdateStatus'])->name('admin.inventory.update-status');
        Route::post('/inventory/{id}/mark-damage', [AdminController::class, 'inventoryMarkDamage'])->name('admin.inventory.mark-damage');
        Route::post('/inventory/{id}/restore-damage', [AdminController::class, 'inventoryRestoreDamage'])->name('admin.inventory.restore-damage');
        Route::post('/inventory/{id}/add-stock', [AdminController::class, 'inventoryAddStock'])->name('admin.inventory.add-stock');
        Route::get('/inventory/{id}/stock-card', [AdminController::class, 'inventoryStockCard'])->name('admin.inventory.stock-card');
        Route::delete('/inventory/{id}', [AdminController::class, 'inventoryDelete'])->name('admin.inventory.delete');

        // ── Payments (Web) ────────────────────────────────────────────────
        Route::get('/payments', [PaymentController::class, 'index'])->name('admin.payments.index');
        Route::get('/payments/{id}', [PaymentController::class, 'show'])->name('admin.payments.show');
        Route::get('/bookings/{id}/payment/create', [PaymentController::class, 'create'])->name('admin.payments.create');
        Route::post('/bookings/{id}/payment/store', [PaymentController::class, 'store'])->name('admin.payments.store');

        // ── Reports (Web) ─────────────────────────────────────────────────
        Route::get('/reports/sales', [PaymentController::class, 'salesReport'])->name('admin.reports.sales');

        // ── API Routes ────────────────────────────────────────────────────
        Route::prefix('api')->group(function () {

            // Bookings
            Route::post('/bookings', [BookingController::class, 'store']);
            Route::get('/bookings', [BookingController::class, 'getByStatus']);
            Route::get('/availability/events', [BookingController::class, 'getCalendarEvents']);
            Route::get('/bookings/{bookingID}', [BookingController::class, 'show']);
            Route::get('/bookings/{bookingID}/items', [BookingController::class, 'getBookingItems']);
            Route::get('/bookings/{bookingID}/pending-payments', [BookingController::class, 'getPendingPayments']);
            Route::put('/bookings/{bookingID}/cancel', [BookingController::class, 'cancel']);
            Route::get('/customers/{customerID}/bookings', [BookingController::class, 'getCustomerBookings']);

            // Payments
            Route::post('/bookings/{bookingID}/payments', [PaymentController::class, 'recordPayment']);
            Route::get('/bookings/{bookingID}/payments', [PaymentController::class, 'getBookingPayments']);
            Route::get('/bookings/{bookingID}/payment-breakdown', [PaymentController::class, 'getPaymentBreakdown']);
            Route::get('/payments', [PaymentController::class, 'getByStatus']);

            // Rentals
            Route::put('/bookings/{bookingID}/start-rental', [RentalController::class, 'startRental']);
            Route::put('/bookings/{bookingID}/process-return', [RentalController::class, 'processReturn']);
            Route::get('/bookings/{bookingID}/rental-details', [RentalController::class, 'getRentalDetails']);
            Route::get('/rentals/in-use', [RentalController::class, 'getInUseBookings']);
            Route::get('/rentals/pending-return', [RentalController::class, 'getPendingReturns']);
            Route::get('/rentals/completed', [RentalController::class, 'getCompleted']);
        });
    });
});