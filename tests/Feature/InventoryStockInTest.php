<?php

namespace Tests\Feature;

use App\Models\Inventory;
use App\Models\InventoryTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryStockInTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_in_page_shows_existing_stock_in_records(): void
    {
        $item = Inventory::create([
            'itemName' => 'Round Table',
            'category' => 'Tables',
            'quantityAvailable' => 12,
            'quantityDamaged' => 0,
            'rentalPrice' => 250,
            'purchase_cost' => 1000,
            'status' => 'Available',
        ]);

        InventoryTransaction::create([
            'itemID' => $item->itemID,
            'type' => 'stock_in',
            'quantity' => 4,
            'available_before' => 8,
            'available_after' => 12,
            'damaged_before' => 0,
            'damaged_after' => 0,
            'notes' => '4 item(s) stocked in.',
        ]);

        $response = $this->withSession(['admin_logged_in' => true])
            ->get(route('admin.inventory.stock-in'));

        $response->assertOk();
        $response->assertSee('Stock In Records');
        $response->assertSee('Round Table');
        $response->assertSee('Existing Item');
    }

    public function test_posting_stock_in_for_existing_item_creates_a_transaction_record(): void
    {
        $item = Inventory::create([
            'itemName' => 'Chiavari Chair',
            'category' => 'Tables',
            'quantityAvailable' => 10,
            'quantityDamaged' => 0,
            'rentalPrice' => 120,
            'purchase_cost' => 450,
            'status' => 'Available',
        ]);

        $response = $this->withSession(['admin_logged_in' => true])
            ->post(route('admin.inventory.stock-in.store'), [
                'type' => 'existing',
                'itemID' => $item->itemID,
                'addQuantity' => 5,
            ]);

        $response->assertRedirect(route('admin.inventory.stock-in'));

        $this->assertDatabaseHas('inventory', [
            'itemID' => $item->itemID,
            'quantityAvailable' => 15,
        ]);

        $this->assertDatabaseHas('inventory_transactions', [
            'itemID' => $item->itemID,
            'type' => 'stock_in',
            'quantity' => 5,
            'available_before' => 10,
            'available_after' => 15,
            'notes' => '5 item(s) stocked in.',
        ]);
    }

    public function test_posting_new_item_creates_a_stock_in_transaction_record(): void
    {
        $response = $this->withSession(['admin_logged_in' => true])
            ->post(route('admin.inventory.stock-in.store'), [
                'type' => 'new',
                'itemName' => 'Dinner Plate',
                'category' => 'Dining Wares',
                'quantityAvailable' => 24,
                'purchase_cost' => 80,
                'rentalPrice' => 15,
            ]);

        $response->assertRedirect(route('admin.inventory.stock-in'));

        $item = Inventory::where('itemName', 'Dinner Plate')->firstOrFail();

        $this->assertDatabaseHas('inventory_transactions', [
            'itemID' => $item->itemID,
            'type' => 'stock_in',
            'quantity' => 24,
            'available_before' => 0,
            'available_after' => 24,
            'notes' => 'New item created with 24 unit(s).',
        ]);
    }
}