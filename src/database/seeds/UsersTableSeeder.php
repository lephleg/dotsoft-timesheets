<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();

        $legitUser = new \App\User;
        $legitUser->username = 'admin';
        $legitUser->password = Hash::make('DOTsoft123');
        $legitUser->save();
    }

}