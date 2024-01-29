<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.hu',
            'password' => Hash::make('adminpwd'),
            'is_admin' => true,
        ]);

        for ($i=0; $i < 5; $i++) {
            User::factory()->create([
                'email' => 'user'.$i.'@test.hu',
            ]);
        }
    }
}
