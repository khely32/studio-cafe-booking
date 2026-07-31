<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@5630studiocafe.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->call([
            ServiceSeeder::class,
            SampleDataSeeder::class,
        ]);
    }
}
