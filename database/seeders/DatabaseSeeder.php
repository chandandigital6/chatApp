<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);

        User::create([
            'name' => 'Admin User',
            'email' => 'super@admin.com',
            'password' => Hash::make('password'),
        ])->assignRole('Super Admin');
        
    }
}
