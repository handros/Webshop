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
        $this->call([
            UserSeeder::class,
            ItemSeeder::class,
            OrderSeeder::class,
            LabelSeeder::class,
            CommentSeeder::class,
            AuctionSeeder::class,
            BidSeeder::class,
            MessageSeeder::class,
        ]);
    }
}
