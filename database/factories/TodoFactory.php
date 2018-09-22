<?php

use Faker\Generator as Faker;

$factory->define(App\Todo::class, function (Faker $faker) {
    $users = \App\User::all()->random(1)->pluck('id')->values();

    return [
        'task' => $faker->sentence,
        'completed' => $faker->boolean(),
        'user_id' => $users[0]
    ];
});
