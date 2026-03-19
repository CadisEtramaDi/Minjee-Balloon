<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call seeders
        $this->call([
            AdminUserSeeder::class,
            InventorySeeder::class,
        ]);

        // Create sample customer
        Customer::create([
            'fname' => 'John',
            'lname' => 'Doe',
            'phonenumber' => '09123456789',
            'address' => '123 Main Street, Manila',
        ]);
    }
}
