<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create IT department
        $itDepartment = Department::firstOrCreate(
            ['code' => 'IT'],
            ['name' => 'Information Technology']
        );

        // Create Admin IT
        User::updateOrCreate(
            ['email' => 'admin'],
            [
            'name' => 'Admin IT',
            'password' => Hash::make('123'),
            'phone' => '1234567890',
            'position' => 'IT',
            'role' => 'admin',
            'status' => 1,
            'department' => $itDepartment->code,
            'email_verified_at' => now(),
            ]
        );
        User::updateOrCreate(
            ['email' => 'administrasi'],
            [
            'name' => 'Admin Administrasi',
            'password' => Hash::make('123'),
            'phone' => '1234567890',
            'position' => 'Administrasi',
            'role' => 'admin',
            'status' => 1,
            'department' => $itDepartment->code,
            'email_verified_at' => now(),
            ]
        );
    }
}