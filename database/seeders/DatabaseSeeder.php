<?php

namespace Database\Seeders;

use \App\Models\Outlet;
use \App\Models\User;
use Illuminate\Support\Facades\Hash;
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
        $outlet = Outlet::create([
            'name' => 'Laundry Pusat',
            'address' => 'Jl. Laundry Bebas',
            'phone_number' => '081234567890',

        ]);

        User::create([
            'name' => 'Admin Laundry',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'outlet_id' => $outlet->id,
            'role' => 'admin',
        ]);
    }
}
