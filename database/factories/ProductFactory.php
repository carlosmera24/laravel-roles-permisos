<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'name'             => $faker->sentence,
        'description'     => $faker->text(500),
    ];
});
