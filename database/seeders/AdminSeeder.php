<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@chedro.gov.ph'], // Unique identifier
            [
                'name' => 'System Administrator',
                'password' => Hash::make('Admin123!'), // Change this immediately after login
                'role' => 'admin', // Matches your controller logic
            ]
        );
    }
}
