<?php

namespace Database\Seeders;

use App\Models\Label;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $labels = Label::factory(10)->create();

        $items = Item::all();
        foreach ($items as $item) {
            $attached_labels = $labels->random(rand(0,3));
            $item->labels()->attach($attached_labels);
        }

        $orders = Order::all();
        foreach ($orders as $order) {
            $attached_labels = $labels->random(rand(0,2));
            $order->labels()->attach($attached_labels);
        }
    }
}
