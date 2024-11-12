<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Webamooz\User\Models\User;

class DatabaseSeeder extends Seeder
{

    public static $seeders = [];  //به صورت استیتک و عمومی در هر سرویس پروایدری استفاده کنیم میاد صدا میزنه درون آرایه میریزه و میاد درون سییدر کال میکنه و صدا میزنه

    public function run()
    {

        foreach (self::$seeders as $seeder) {
            $this->call($seeder);
        }

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

    }
}
