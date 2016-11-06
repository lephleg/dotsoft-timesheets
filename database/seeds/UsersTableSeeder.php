<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder {

    public function run()
    {
        $legitUser = new \App\User;
        $legitUser->username = 'admin';
        $legitUser->password = Hash::make('.DOTsoft,2016');
        $legitUser->save();
    }

}