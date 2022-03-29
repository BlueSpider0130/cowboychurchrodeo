<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Series;
use Faker\Generator as Faker;

$factory->define(Series::class, function (Faker $faker) {

    $start = $faker->dateTimeBetween("-180 days", "+180 days");
    $end   = $faker->dateTimeBetween($start, "+180 days");

    return [

        'organization_id' => function() {
            return factory( Organization::class )->create()->id;
        },

        'name' => $faker->words( $faker->numberBetween(2, 3), true ) . ' series',
        'description' => $faker->sentence,
        'starts_at' => $start,
        'ends_at' => $end
    ];

});


$factory->state(Series::class, 'with-membership-fee', function ($faker) {
    return [
        'membership_fee' => $faker->numberBetween(1, 1000),
    ];
});


$factory->state(Series::class, 'tba', function ($faker) {
    return [
        'starts_at' => null,
        'ends_at'   => null
    ];
});


$factory->state(Series::class, 'tba-end', function ($faker) {
    return [
        'ends_at' => null
    ];
});
