<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    \App\Models\User::create([
        'name' => 'System Administrator', // [cite: 90]
        'email' => 'admin@unifast.gov.ph', // [cite: 91]
        'password' => bcrypt('admin123'), // Default password
        'role' => 'admin',
    ]);
}
}
