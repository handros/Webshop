<?php

namespace Database\Seeders;

use App\Models\Label;
use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = Item::all();

        $labels = Label::factory(10)->create();

        foreach ($items as $item) {
            $attached_labels = $labels->random(rand(0,3));
            $item->labels()->attach($attached_labels);
        }
    }
}
