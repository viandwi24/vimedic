<?php

use App\User;
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
            'name' => 'Example Admin',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
        User::create([
            'name' => 'Example Employee',
            'username' => 'employee',
            'password' => Hash::make('password'),
            'role' => 'employee'
        ]);
        User::create([
            'name' => 'Example Doctor',
            'username' => 'doctor',
            'password' => Hash::make('password'),
            'role' => 'doctor'
        ]);
    }
}
