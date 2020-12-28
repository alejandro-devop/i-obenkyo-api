<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
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
//        User::truncate();
        $password = Hash::make('JKrules12121992');
        User::create([
            'name'      => 'Alejandro Quiroz',
            'email'     => 'alejandro.devop@gmail.com',
            'password'  => $password,
        ]);
    }
}
