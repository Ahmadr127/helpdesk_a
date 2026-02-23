<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;

class AdministrasiPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah posisi administrasi sudah ada
        $exists = Position::where('name', 'administrasi')->exists();

        if (!$exists) {
            Position::create([
                'name' => 'administrasi', // Harus exact 'administrasi' untuk login
                'code' => 'ADM',
                'status' => true,
            ]);
        }
    }
} 