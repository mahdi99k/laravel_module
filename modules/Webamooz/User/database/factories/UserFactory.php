<?php

namespace Webamooz\User\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Webamooz\User\Models\User;


class UserFactory extends Factory
{

    protected $model = User::class;  //Connection Model in Factories

    public function definition()
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'mobile' => '939818' . rand(1000, 9999),
            'email_verified_at' => now(),
            'password' => \Hash::make('12aBC!@'),
            'remember_token' => Str::random(10),

        ];
    }

    public function unverified()
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

}
