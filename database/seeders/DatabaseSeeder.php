<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();


                User::create([
            'name' => 'System Admin',
            'email' => 'admin@school.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Dean OSAD',
            'email' => 'dean@school.com',
            'password' => Hash::make('dean123'),
            'role' => 'dean',
        ]);

        User::create([
            'name' => 'Org Officer',
            'email' => 'officer@school.com',
            'password' => Hash::make('officer123'),
            'role' => 'officer',
        ]);

    }
}
