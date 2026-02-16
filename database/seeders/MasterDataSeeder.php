<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Department;
use App\Models\Building;
use App\Models\Location;

class MasterDataSeeder extends DatabaseSeeder
{
    public function run(): void
    {
        // Categories
        $categories = [
            ['name' => 'Termedic', 'status' => 1],
            ['name' => 'Printer', 'status' => 1],
            ['name' => 'CCTV', 'status' => 1],
            ['name' => 'Software', 'status' => 1],
            ['name' => 'Hardware', 'status' => 1],
        ];
        
        foreach ($categories as $category) {
            Category::create($category);
        }

        // Buildings
        $buildings = [
            ['name' => 'Gedung A', 'code' => 'A', 'status' => 1],
            ['name' => 'Gedung B', 'code' => 'B', 'status' => 1],
            ['name' => 'Gedung C', 'code' => 'C', 'status' => 1],
        ];

        foreach ($buildings as $building) {
            Building::create($building);
        }

        // Locations
        $locations = [
            ['name' => 'UGD', 'building_id' => 1, 'status' => 1], // Gedung A
            ['name' => 'BPJS', 'building_id' => 2, 'status' => 1], // Gedung B
            ['name' => 'Parkiran', 'building_id' => 3, 'status' => 1], // Gedung C
            ['name' => 'Taman Bermain Anak', 'building_id' => 3, 'status' => 1], // Gedung C
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}