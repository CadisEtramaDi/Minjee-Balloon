<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // =========================================================================
    // AUTH
    // =========================================================================

    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = \App\Models\User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            session([
                'admin_logged_in' => true,
                'user_id'         => $user->userID,
                'username'        => $user->username,
                'role'            => $user->role,
            ]);
            return redirect()->route('admin.dashboard')
                ->with('success', 'Welcome back, ' . ucfirst($user->username) . '!');
        }

        return back()->with('error', 'Invalid credentials. Please try again.');
    }

    public function logout()
    {
        session()->forget('admin_logged_in');
        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out successfully.');
    }

    // =========================================================================
    // DASHBOARD
    // =========================================================================

    public function dashboard()
    {
        $totalBookings     = Booking::count();
        $pendingBookings   = Booking::where('status', 'Pending')->count();
        $confirmedBookings = Booking::where('status', 'Confirmed')->count();
        $cancelledBookings = Booking::where('status', 'Cancelled')->count();
        $paidBookings      = Booking::where('status', 'Completed')->count();
        $recentBookings    = Booking::with('customer', 'payments')->latest()->take(10)->get();

        $totalSales = \App\Models\Payment::where('payment_status', 'completed')->sum('amountpaid');
        $monthSales = \App\Models\Payment::where('payment_status', 'completed')
            ->whereMonth('paymentdate', now()->month)
            ->sum('amountpaid');

        $lowStockAlerts = \App\Models\Inventory::whereRaw('(quantityAvailable - quantityDamaged) <= 5')
            ->where('status', 'Available')
            ->get();

        return view('admin.dashboard', compact(
            'totalBookings',
            'pendingBookings',
            'confirmedBookings',
            'cancelledBookings',
            'paidBookings',
            'recentBookings',
            'totalSales',
            'monthSales',
            'lowStockAlerts'
        ));
    }

    // =========================================================================
    // CUSTOMERS
    // =========================================================================

    public function customersIndex(Request $request)
    {
        $query = \App\Models\Customer::with('bookings');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('fname', 'like', "%{$search}%")
                  ->orWhere('lname', 'like', "%{$search}%")
                  ->orWhere('phonenumber', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
        }

        $customers      = $query->latest()->paginate(20);
        $totalCustomers = \App\Models\Customer::count();
        $activeBookings = Booking::where('status', '!=', 'Cancelled')->count();
        $pendingPayments = \App\Models\Payment::where('payment_status', '!=', 'completed')->count();

        return view('admin.customers.index', compact(
            'customers',
            'totalCustomers',
            'activeBookings',
            'pendingPayments'
        ));
    }

    public function customersShow($id)
    {
        $customer = \App\Models\Customer::with('bookings.payments')->findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }

    // =========================================================================
    // INVENTORY
    // =========================================================================

    public function inventoryCreate()
    {
        return view('admin.inventory.create');
    }

    public function inventoryStore(Request $request)
    {
        $request->validate([
            'itemName'          => 'required|string|max:100',
            'category'          => 'required|in:Tables,Dining Wares,Catering Equipment,Entertainment',
            'quantityAvailable' => 'required|integer|min:0',
            'purchase_cost'     => 'required|numeric|min:0',
            'rentalPrice'       => 'required|numeric|min:0',
            'status'            => 'required|in:Available,Damaged,Unavailable',
        ]);

        $item = Inventory::create($request->only([
            'itemName', 'category', 'quantityAvailable',
            'purchase_cost', 'rentalPrice', 'status',
        ]));

        return redirect()->route('admin.inventory.show', $item->itemID)
            ->with('success', 'Inventory item created successfully!');
    }

    public function inventoryIndex(Request $request)
    {
        $query = Inventory::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('itemName', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
        }

        $items           = $query->orderBy('itemName')->paginate(20);
        $totalItems      = Inventory::count();
        $availableItems  = Inventory::where('status', 'Available')->count();
        $lowStockItems   = Inventory::where('quantityAvailable', '<=', 5)->count();
        $unavailableItems= Inventory::where('status', 'Unavailable')->count();
        $damagedItems    = Inventory::where('quantityDamaged', '>', 0)->get();

        return view('admin.inventory.index', compact(
            'items', 'totalItems', 'availableItems',
            'lowStockItems', 'unavailableItems', 'damagedItems'
        ));
    }

    public function inventoryShow($id)
    {
        $item         = Inventory::with('bookingItems.booking.customer')->findOrFail($id);
        $totalRented  = $item->bookingItems->sum('quantity');
        $totalRevenue = $item->bookingItems->sum('subtotal');

        return view('admin.inventory.show', compact('item', 'totalRented', 'totalRevenue'));
    }

    public function inventoryEdit($id)
    {
        $item = Inventory::findOrFail($id);
        return view('admin.inventory.edit', compact('item'));
    }

    public function inventoryUpdate(Request $request, $id)
    {
        $request->validate([
            'itemName'          => 'required|string|max:100',
            'category'          => 'required|in:Tables,Dining Wares,Catering Equipment,Entertainment',
            'quantityAvailable' => 'required|integer|min:0',
            'purchase_cost'     => 'required|numeric|min:0',
            'rentalPrice'       => 'required|numeric|min:0',
            'status'            => 'required|in:Available,Damaged,Unavailable',
        ]);

        $item = Inventory::findOrFail($id);
        $item->update($request->only([
            'itemName', 'category', 'quantityAvailable',
            'purchase_cost', 'rentalPrice', 'status',
        ]));

        return redirect()->route('admin.inventory.show', $item->itemID)
            ->with('success', 'Inventory item updated successfully!');
    }

    public function inventoryDelete($id)
    {
        Inventory::findOrFail($id)->delete();
        return redirect()->route('admin.inventory.index')
            ->with('success', 'Inventory item deleted successfully!');
    }

    public function inventoryUpdateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:Available,Damaged,Unavailable']);

        $item = Inventory::findOrFail($id);
        $item->status = $request->status;
        $item->save();

        $messages = [
            'Available'   => 'Item marked as available.',
            'Damaged'     => 'Item marked as damaged.',
            'Unavailable' => 'Item marked as out of stock.',
        ];

        return back()->with('success', $messages[$request->status]);
    }

    public function inventoryMarkDamage(Request $request, $id)
    {
        $request->validate(['damageQuantity' => 'required|integer|min:1']);

        $item      = Inventory::findOrFail($id);
        $damageQty = (int) $request->damageQuantity;
        $canDamage = $item->quantityAvailable - $item->quantityDamaged;

        if ($damageQty > $canDamage) {
            return back()->withErrors([
                'damageQuantity' => "Cannot mark {$damageQty} items as damaged. Only {$canDamage} available.",
            ])->withInput();
        }

        $availableBefore = $item->quantityAvailable;
        $damagedBefore   = $item->quantityDamaged;
        $item->quantityDamaged += $damageQty;
        $item->save();

        InventoryTransaction::create([
            'itemID'           => $item->itemID,
            'type'             => 'damage',
            'quantity'         => $damageQty,
            'available_before' => $availableBefore,
            'available_after'  => $item->quantityAvailable,
            'damaged_before'   => $damagedBefore,
            'damaged_after'    => $item->quantityDamaged,
            'notes'            => "{$damageQty} item(s) marked as damaged.",
        ]);

        return back()->with('success', "{$damageQty} item(s) marked as damaged.");
    }

    public function inventoryAddStock(Request $request, $id)
    {
        $request->validate(['addQuantity' => 'required|integer|min:1']);

        $item     = Inventory::findOrFail($id);
        $addedQty = (int) $request->addQuantity;

        $availableBefore = $item->quantityAvailable;
        $damagedBefore   = $item->quantityDamaged;

        $item->quantityAvailable += $addedQty;
        if ($item->status === 'Unavailable') {
            $item->status = 'Available';
        }
        $item->save();

        InventoryTransaction::create([
            'itemID'           => $item->itemID,
            'type'             => 'stock_in',
            'quantity'         => $addedQty,
            'available_before' => $availableBefore,
            'available_after'  => $item->quantityAvailable,
            'damaged_before'   => $damagedBefore,
            'damaged_after'    => $item->quantityDamaged,
            'notes'            => "{$addedQty} new item(s) added to stock.",
        ]);

        return back()->with('success', "{$addedQty} item(s) added! Total is now {$item->quantityAvailable}.");
    }

    public function inventoryRestoreDamage(Request $request, $id)
    {
        $request->validate(['restoreQuantity' => 'required|integer|min:1']);

        $item       = Inventory::findOrFail($id);
        $restoreQty = (int) $request->restoreQuantity;

        if ($restoreQty > $item->quantityDamaged) {
            return back()->withErrors([
                'restoreQuantity' => "Cannot restore {$restoreQty} items. Only {$item->quantityDamaged} damaged.",
            ])->withInput();
        }

        $availableBefore = $item->quantityAvailable;
        $damagedBefore   = $item->quantityDamaged;
        $item->quantityDamaged -= $restoreQty;
        $item->save();

        InventoryTransaction::create([
            'itemID'           => $item->itemID,
            'type'             => 'restore',
            'quantity'         => $restoreQty,
            'available_before' => $availableBefore,
            'available_after'  => $item->quantityAvailable,
            'damaged_before'   => $damagedBefore,
            'damaged_after'    => $item->quantityDamaged,
            'notes'            => "{$restoreQty} damaged item(s) restored.",
        ]);

        return back()->with('success', "{$restoreQty} item(s) restored to available inventory.");
    }

    public function inventoryStockCard($id)
    {
        $item         = Inventory::findOrFail($id);
        $transactions = InventoryTransaction::where('itemID', $id)
            ->orderBy('created_at', 'desc')->get();

        return view('admin.inventory.stock-card', compact('item', 'transactions'));
    }

    public function inventoryStockInPage()
    {
        $items        = Inventory::orderBy('itemName')->get();
        $transactions = InventoryTransaction::with('item')
            ->where('type', 'stock_in')
            ->orderBy('created_at', 'desc')
            ->limit(15)->get();

        return view('admin.inventory.stock-in', compact('items', 'transactions'));
    }

    public function inventoryStockInStore(Request $request)
    {
        $request->validate(['type' => 'required|in:existing,new']);

        if ($request->type === 'existing') {
            $request->validate([
                'itemID'      => 'required|exists:inventory,itemID',
                'addQuantity' => 'required|integer|min:1',
            ]);

            $item     = Inventory::findOrFail($request->itemID);
            $addedQty = (int) $request->addQuantity;

            $availableBefore = $item->quantityAvailable;
            $damagedBefore   = $item->quantityDamaged;

            $item->quantityAvailable += $addedQty;
            if ($item->status === 'Unavailable') $item->status = 'Available';
            $item->save();

            InventoryTransaction::create([
                'itemID'           => $item->itemID,
                'type'             => 'stock_in',
                'quantity'         => $addedQty,
                'available_before' => $availableBefore,
                'available_after'  => $item->quantityAvailable,
                'damaged_before'   => $damagedBefore,
                'damaged_after'    => $item->quantityDamaged,
                'notes'            => "{$addedQty} item(s) stocked in.",
            ]);

            return redirect()->route('admin.inventory.stock-in')
                ->with('success', "{$addedQty} item(s) added to {$item->itemName}. Total: {$item->quantityAvailable}.");
        }

        $request->validate([
            'itemName'          => 'required|string|max:100',
            'category'          => 'required|in:Tables,Dining Wares,Catering Equipment,Entertainment',
            'quantityAvailable' => 'required|integer|min:1',
            'purchase_cost'     => 'required|numeric|min:0',
            'rentalPrice'       => 'required|numeric|min:0',
        ]);

        $item = Inventory::create([
            'itemName'          => $request->itemName,
            'category'          => $request->category,
            'quantityAvailable' => $request->quantityAvailable,
            'purchase_cost'     => $request->purchase_cost,
            'rentalPrice'       => $request->rentalPrice,
            'status'            => 'Available',
        ]);

        InventoryTransaction::create([
            'itemID'           => $item->itemID,
            'type'             => 'stock_in',
            'quantity'         => $item->quantityAvailable,
            'available_before' => 0,
            'available_after'  => $item->quantityAvailable,
            'damaged_before'   => 0,
            'damaged_after'    => 0,
            'notes'            => "New item created with {$item->quantityAvailable} unit(s).",
        ]);

        return redirect()->route('admin.inventory.stock-in')
            ->with('success', "New item \"{$item->itemName}\" created with {$item->quantityAvailable} unit(s).");
    }

    public function inventoryStockOutPage()
    {
        $items = Inventory::where('quantityAvailable', '>', 0)->orderBy('itemName')->get();
        return view('admin.inventory.stock-out', compact('items'));
    }

    public function inventoryStockOutStore(Request $request)
    {
        $request->validate([
            'itemID'   => 'required|exists:inventory,itemID',
            'quantity' => 'required|integer|min:1',
            'reason'   => 'required|in:damaged,write_off',
        ]);

        $item      = Inventory::findOrFail($request->itemID);
        $qty       = (int) $request->quantity;
        $canStock  = $item->quantityAvailable - $item->quantityDamaged;

        if ($qty > $canStock) {
            return back()->withErrors([
                'quantity' => "Cannot stock out {$qty} items. Only {$canStock} usable item(s) available.",
            ])->withInput();
        }

        $availableBefore = $item->quantityAvailable;
        $damagedBefore   = $item->quantityDamaged;

        if ($request->reason === 'damaged') {
            $item->quantityDamaged += $qty;
            $item->save();
            $note = "{$qty} item(s) marked as damaged.";
        } else {
            $item->quantityAvailable -= $qty;
            if ($item->quantityAvailable <= 0) $item->status = 'Unavailable';
            $item->save();
            $note = "{$qty} item(s) written off from inventory.";
        }

        InventoryTransaction::create([
            'itemID'           => $item->itemID,
            'type'             => 'stock_out',
            'quantity'         => $qty,
            'available_before' => $availableBefore,
            'available_after'  => $item->quantityAvailable,
            'damaged_before'   => $damagedBefore,
            'damaged_after'    => $item->quantityDamaged,
            'notes'            => $note,
        ]);

        return redirect()->route('admin.inventory.stock-out')->with('success', $note);
    }

    // =========================================================================
    // OLD BOOKING METHODS — DISABLED (moved to BookingController)
    // =========================================================================

    /*
    public function bookingsIndex(Request $request) { ... }
    public function bookingsCreate() { ... }
    public function bookingsStore(Request $request) { ... }
    public function bookingsShow($id) { ... }
    public function bookingsEdit($id) { ... }
    public function updateBookingStatus(Request $request, $id) { ... }
    public function deleteBooking($id) { ... }
    public function cancelBooking($id) { ... }
    public function recordPayment(Request $request, $id) { ... }
    public function startRental(Request $request, $id) { ... }
    public function processReturn(Request $request, $id) { ... }
    public function returnItems(Request $request, $id) { ... }
    public function checkAvailability(Request $request) { ... }
    private function normalizeBookingStatus(string $status): ?string { ... }
    */
}