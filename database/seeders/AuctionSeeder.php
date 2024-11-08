<?php

namespace Database\Seeders;

use App\Models\Auction;
use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = Item::where('on_auction', true)->get();

        foreach ($items as $item) {
            $bidders = User::inRandomOrder()->take(rand(2, 4))->get();
            $auction = Auction::factory()
                ->for($item)
                ->create();
            foreach ($bidders as $bidder) {
                $auction->users()->attach($bidder->id);
            }
        }
    }
}
