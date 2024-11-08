<?php

namespace Database\Seeders;


use App\Models\Message;
use App\Models\User;
use App\Models\Auction;
use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        $auctions = Auction::all();
        for($i = 0; $i < 30; $i++) {
            $sender = $users->random();
            $receiver = $users->where('id', '!=', $sender->id)->random();
            $auction = $auctions->random();

            Message::factory()->create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'auction_id' => $auction->id,
                'order_id' => null,
            ]);
        }

        $orders = Order::all();
        for($i = 0; $i < 6; $i++) {
            $sender = $users->random();
            $receiver = $users->where('id', '!=', $sender->id)->random();
            $order = $orders->random();

            Message::factory()->create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'auction_id' => null,
                'order_id' => $order->id,
            ]);
        }
    }
}
