<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = Item::all();
        $users = User::all();
        foreach ($items as $item) {
            $commenters = $users->random(rand(1,3));
            foreach ($commenters as $commenter) {
                Comment::factory()
                    ->for($item)
                    ->create(['user_id' => $commenter->id]);
            }
        }
    }
}
