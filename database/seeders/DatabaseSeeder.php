<?php

namespace Database\Seeders;

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
        $this->call(DepartmentSeeder::class);

        $this->call(MasterDataSeeder::class);
        // Create admin user first
        $this->call(AdminSeeder::class);
        // Create master data


        // Create departments after master data
        // Create test user last
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}