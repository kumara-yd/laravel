<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NavigationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $navigations = [
            [
                'name' => 'Home',
                'url' => 'home',
                'icon' => 'bi bi-house',
                'order' => 1,
                'parent_id' => null,
                'active' => true,
                'display' => true,
            ],
            [
                'name' => 'Settings',
                'url' => 'settings',
                'icon' => 'bi bi-sliders',
                'order' => 2,
                'parent_id' => null,
                'active' => true,
                'display' => true,
            ],
            [
                'name' => 'User',
                'url' => 'settings.users',
                'icon' => '', // Assuming no icon specified
                'order' => 1,
                'parent_id' => 2, // Nested under Settings
                'active' => true,
                'display' => true,
            ],
        ];
        // Insert data into the 'navigations' table
        DB::table('navigations')->insert($navigations);
    }
}
