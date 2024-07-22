<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(NavigationSeeder::class);
        $this->call(PreferenceSeeder::class);
        $this->call(UserSeeder::class);
    }
}
