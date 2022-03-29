<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Competition;
use Faker\Generator as Faker;

$factory->define(Competition::class, function (Faker $faker) {
    return [

        'organization_id' => function() {
            return factory( \App\Organization::class )->create()->id;
        }, 

        'name' => $faker->words( $faker->numberBetween(2, 3), true ) . ' competition', 
        'description' => $faker->sentence,

        'entry_fee' => $faker->boolean ? $faker->numberBetween(0, 100) : null,

        'max_entries' => $faker->numberBetween(1, 5) < 2 ? $faker->numberBetween(1, 40) : null,
        'max_entries_per_contestant' => $faker->numberBetween(1, 5) < 2 ? $faker->numberBetween(1, 20) : null,
                
    ];
});

$factory->state(Competition::class, 'with-instances', []);
$factory->afterCreatingState( Competition::class, 'with-instances', function ($competition, $faker) {
    factory( \App\CompetitionInstance::class )->create([
        'competition_id' => $competition->id
    ]);
    factory( \App\CompetitionInstance::class, 2 )->states(['with-start-end-times'])->create([
        'competition_id' => $competition->id
    ]);    
});

