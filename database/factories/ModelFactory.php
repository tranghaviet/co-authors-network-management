<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name'           => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'password'       => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Author::class, function (Faker\Generator $faker) {
    $university = \App\Models\University::inRandomOrder()->first();

    return [
        'id'            => str_random(12),
        'given_name'    => $faker->firstName(),
        'surname'       => $faker->lastName,
        'email'         => $faker->unique()->safeEmail,
        'university_id' => $university['id'],
    ];
});
