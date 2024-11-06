<?php

namespace Database\Seeders;


use App\Models\Message;
use App\Models\User;
use App\Models\Auction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $auctions = Auction::all();
        $users = User::all();
        for($i = 0; $i < 30; $i++) {
            $sender = $users->random();
            $receiver = $users->where('id', '!=', $sender->id)->random();
            $auction = $auctions->random();

            Message::factory()->create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'auction_id' => $auction->id,
            ]);
        }
    }
}
