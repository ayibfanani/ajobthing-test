<?php

use App\Jobs\Job;
use Faker\Generator as Faker;

$factory->define(Job::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'title' => $faker->sentence,
        'body' => $faker->paragraph,
        'budget' => 1000,
        'status' => 1,
    ];
});
