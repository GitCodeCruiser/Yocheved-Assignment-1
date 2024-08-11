<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'admin@student-tracker.com',
        ],
        [
            'name' => "Admin",
            'email' => 'admin@student-tracker.com',
            'password' => Hash::make('Admin@123')
        ]);
    }
}
