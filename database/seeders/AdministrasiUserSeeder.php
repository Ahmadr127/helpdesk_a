<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdministrasiUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah user administrasi sudah ada
        $exists = User::where('email', 'administrasi@rs-azra.com')->exists();

        if (!$exists) {
            User::create([
                'name' => 'Administrasi',
                'email' => 'administrasi@rs-azra.com',
                'password' => Hash::make('12345678'),
                'role' => 'admin', // Harus 'admin' untuk bisa login
                'position' => 'administrasi', // Harus exact 'administrasi' untuk redirect ke dashboard administrasi
                'department' => 'Administrasi Umum',
                'status' => true,
            ]);
        }
    }
} 