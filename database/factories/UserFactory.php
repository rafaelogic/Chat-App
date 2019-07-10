<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Message;
use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

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

$factory->define(User::class, function (Faker $faker) {
    return [
        'name'  => $faker->name,
        'phone' => $faker->phoneNumber,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password'      => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'profile_pic'   => 'https://via.placeholder.com/150',
        'remember_token' => Str::random(10),
    ];
});

$factory->define(Message::class, function(Faker $faker) {
    do {
        $from = rand(1, 15);
        $to   = rand(1, 15);
    } while ($from === $to);

    return [
        'from'  => $from,
        'to'    => $to,
        'body'  => $faker->sentence,
    ];
});
