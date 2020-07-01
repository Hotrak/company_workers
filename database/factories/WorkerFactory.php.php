<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->defineAs(\App\Worker::class,'worker', function (Faker $faker) {
    $faker = \Faker\Factory::create('ru_RU');
    return [
        'name' => $faker->firstNameMale,
        'surname' => $faker->lastName,
        'img' => "https://picsum.photos/id/".$faker->numberBetween(1,100)."/200/300",
        'patronymic' => 'Иванович',
        'employment_date' => $faker->date(),
        'salary' => $faker->numberBetween(1000,6000),

    ];
});

$factory->defineAs(\App\Position::class,'worker', function (Faker $faker) {
    $faker = \Faker\Factory::create('ru_RU');
//    $faker->
    return [
        'name' => 'Директор',
    ];
});
