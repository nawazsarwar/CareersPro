<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'                 => 1,
                'name'               => 'Admin',
                'email'              => 'sampark.nawaz@gmail.com',
                'password'           => bcrypt('Zaq!@#45'),
                'remember_token'     => null,
                'verified'           => 1,
                'verified_at'        => '2023-08-11 14:13:02',
                'verification_token' => '',
            ],
        ];

        User::insert($users);
    }
}