<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CompetitionInstance;
use Faker\Generator as Faker;

$factory->define(CompetitionInstance::class, function (Faker $faker) {
    return [
        'competition_id' => function() {
            return factory( \App\Competition::class )->create()->id;
        }, 
        'description' => $faker->sentence,
        'location' => $faker->address,
        'all_day' => true,
        'starts_at' => $faker->date,        
    ];
});

$factory->state(CompetitionInstance::class, 'with-start-end-times', function (Faker $faker) {
    return [
        'all_day' => false,
        'starts_at' => $faker->dateTime,    
        'starts_at' => $faker->dateTime,    
    ];
});
