<?php

use Faker\Generator as Faker;

$factory->define(App\Roles\Role::class, function (Faker $faker) {
    return [
        'key' => 'freelancer',
        'name' => 'Freelancer',
    ];
});
