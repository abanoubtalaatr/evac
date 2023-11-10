<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
           'mobile' => '500000000',
            'email' => 'testuser@gmail.com',
            'is_active' => 1,
            'password' => Hash::make('P@ssword12'),
            'username' => 'Test user',
        ]);
    }
}
