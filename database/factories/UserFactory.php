<?php

use Faker\Generator as Faker;
use Faker\Factory as FakerFactory;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {

    $faker = FakerFactory::create('ja_JP');

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
    ];
});
