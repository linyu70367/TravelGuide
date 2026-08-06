<?php

namespace Database\Seeders;

use App\Models\Manager;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Manager::create([
            'userName' => 'aaa111',
            'pwd' => 'aaa'
        ]);

        Manager::create([
            'userName' => 'bbb222',
            'pwd' => 'bbb'
        ]);
    }
}
