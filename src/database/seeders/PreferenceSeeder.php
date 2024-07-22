<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'group' => 'site',
                'name' => 'title',
                'value' => 'Laravel',
            ],
            [
                'group' => 'site',
                'name' => 'copyright',
                'value' => '&copy; Copyright <strong><span>Fortis Solution</span></strong>. All Rights Reserved',
            ],
            [
                'group' => 'site',
                'name' => 'credits',
                'value' => 'Designed by <a href="https://fortissolution.id/">Fortis Solution</a>',
            ],
            [
                'group' => 'site',
                'name' => 'logo',
                'value' => 'logo.png',
            ],
            [
                'group' => 'site',
                'name' => 'favicon',
                'value' => 'favicon.png',
            ]
        ];
        
        DB::table('preferences')->insert($data);
    }
}
