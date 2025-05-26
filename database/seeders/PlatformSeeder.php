<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Platform::create(['name' => 'platform one', 'type' => 'Facebook']);
        Platform::create(['name' => 'platform two', 'type' => 'Twitter']);
        Platform::create(['name' => 'platform three', 'type' => 'Instagram']);
    }
}
