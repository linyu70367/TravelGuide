<?php

namespace Database\Seeders;

use App\Models\MemberWishlist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MemberWishlist::factory()->count(50)->create();
    }
}
