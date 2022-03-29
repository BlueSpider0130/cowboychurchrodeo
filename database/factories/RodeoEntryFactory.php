<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\RodeoEntry;
use Faker\Generator as Faker;

$factory->define(RodeoEntry::class, function (Faker $faker) {
    return [
        'contestant_id' => function() {
            return factory( \App\Contestant::class )->create()->id;
        }, 
        'rodeo_id' => function() {
            return factory( \App\Rodeo::class )->create()->id;
        }
    ];
});
