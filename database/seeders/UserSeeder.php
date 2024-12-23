<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create an admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@cart.com',
            'role' => 'admin', 
            'password' => Hash::make('password'),
        ]);

        // Create a customer user
        User::create([
            'name' => 'Customer User',
            'email' => 'customer@cart.com',
            'role' => 'customer',
            'password' => Hash::make('password'), 
        ]);
    }
}