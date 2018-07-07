<?php

use Faker\Generator as Faker;

$factory->define(App\Words::class, function (Faker $faker) {
    return [
        //
	'word' => $faker->realText(10,1)
    ];
});
