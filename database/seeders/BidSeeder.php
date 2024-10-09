<?php

namespace Database\Seeders;

use App\Models\Bid;
use App\Models\User;
use App\Models\Auction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $auctions = Auction::all();

        foreach ($auctions as $auction) {
            foreach ($users->random(rand(2,5)) as $user) {
                Bid::factory()->create([
                    'user_id' => $user->id,
                    'auction_id' => $auction->id,
                ]);
            }
        }
    }
}
