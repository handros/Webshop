<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orderers = User::where('id', '!=', 1)->inRandomOrder()->take(3)->get();
        foreach ($orderers as $orderer) {
            Order::factory()->create([
                'orderer_id' => $orderer->id,
            ]);
        }
    }
}
