<?php

namespace Database\Seeders;

use App\Models\Inventory;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            // TABLES
            ['itemName' => 'Square Tables', 'category' => 'Tables', 'quantityAvailable' => 20, 'rentalPrice' => 500, 'status' => 'Available'],
            ['itemName' => 'Round Tables', 'category' => 'Tables', 'quantityAvailable' => 25, 'rentalPrice' => 600, 'status' => 'Available'],
            ['itemName' => 'Chairs', 'category' => 'Tables', 'quantityAvailable' => 100, 'rentalPrice' => 150, 'status' => 'Available'],
            ['itemName' => 'Tents', 'category' => 'Tables', 'quantityAvailable' => 10, 'rentalPrice' => 3000, 'status' => 'Available'],
            ['itemName' => 'Chair Covers', 'category' => 'Tables', 'quantityAvailable' => 150, 'rentalPrice' => 50, 'status' => 'Available'],

            // DINING WARES
            ['itemName' => 'Plates', 'category' => 'Dining Wares', 'quantityAvailable' => 200, 'rentalPrice' => 25, 'status' => 'Available'],
            ['itemName' => 'Glasses', 'category' => 'Dining Wares', 'quantityAvailable' => 200, 'rentalPrice' => 30, 'status' => 'Available'],
            ['itemName' => 'Utensils', 'category' => 'Dining Wares', 'quantityAvailable' => 300, 'rentalPrice' => 20, 'status' => 'Available'],
            ['itemName' => 'Bowls', 'category' => 'Dining Wares', 'quantityAvailable' => 100, 'rentalPrice' => 35, 'status' => 'Available'],
            ['itemName' => 'Goblets', 'category' => 'Dining Wares', 'quantityAvailable' => 150, 'rentalPrice' => 40, 'status' => 'Available'],

            // CATERING EQUIPMENTS
            ['itemName' => 'Buffet Tables', 'category' => 'Catering Equipment', 'quantityAvailable' => 15, 'rentalPrice' => 800, 'status' => 'Available'],
            ['itemName' => 'Food Warmers', 'category' => 'Catering Equipment', 'quantityAvailable' => 20, 'rentalPrice' => 400, 'status' => 'Available'],
            ['itemName' => 'Soup Warmers', 'category' => 'Catering Equipment', 'quantityAvailable' => 15, 'rentalPrice' => 350, 'status' => 'Available'],
            ['itemName' => 'Juice Dispenser', 'category' => 'Catering Equipment', 'quantityAvailable' => 10, 'rentalPrice' => 500, 'status' => 'Available'],
            ['itemName' => 'Buffet Lamps', 'category' => 'Catering Equipment', 'quantityAvailable' => 25, 'rentalPrice' => 200, 'status' => 'Available'],

            // ENTERTAINMENT RENTALS
            ['itemName' => 'Videoke Machines', 'category' => 'Entertainment', 'quantityAvailable' => 8, 'rentalPrice' => 2000, 'status' => 'Available'],
        ];

        foreach ($items as $item) {
            Inventory::create($item);
        }
    }
}
