<?php

namespace Webamooz\User\database\Seeders;

use Illuminate\Database\Seeder;
use Webamooz\User\Models\User;

class UsersTableSeeder extends Seeder
{

    public function run()
    {
        //findOrCreate -> بیا پیدا کن اگر وجود داشت کاری نکن ولی اگر وجود نداشت بیا بساز + یک دیتا میگیره به صورت استرینگ و اسم دیتا
        foreach (User::$defaultUsers as $user) {
            User::firstOrCreate(
                ['email' => $user['email'], 'name' => $user['name']]  //شرطی که گذاشتی برای قسمت first بیا پیدا کنه مقدار کاربر وگرنه بسازش
                ,[
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => bcrypt($user['password'])
            ])->assignRole($user['role'])->markEmailAsVerified();  //email verified -> markEmailAsVerified
        }
    }

}
