<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CompetitionEntry;
use Faker\Generator as Faker;

$factory->define(CompetitionEntry::class, function (Faker $faker) {
    return [
        'competition_id' => function() {
            return factory( \App\Competition::class )->create()->id;
        },
        'contestant_id' => function() {
            return factory( \App\Contestant::class )->create()->id;
        }, 
    ];
});

$factory->state(CompetitionEntry::class, 'no-fee', function ($faker) {
    return [
        'no_fee' => true,
    ];
});

$factory->state(CompetitionEntry::class, 'no-score', function ($faker) {
    return [
        'no_score' => true,
    ];
});

$factory->state(CompetitionEntry::class, 'team-roping', function ($faker) {
    return [
        'position' => $faker->randomElement(['header', 'heeler'])
    ];
});
