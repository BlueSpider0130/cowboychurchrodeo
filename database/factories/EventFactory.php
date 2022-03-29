<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Event;
use App\Organization;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    return [
        'organization_id' => function() {
            return factory( Organization::class )->create()->id;
        },       
           
        'name' => function() use ($faker) {
            if( $faker->numberBetween(0, 4) > 0 )
            {
                $events = ["Mutton Bustin'", "Goat Ribbon Pull", "Pee-Wee Poles", "Pee-Wee Barrels", "Calf Riding", "Pony Bronc", "Goat Ribbon Pull (Horseless)", "Goat Tying", "Goat Tying (Horseless)", "Dummy Breakaway (Horse Required)", "Breakaway Roping", "Member/Parent Ribbon Roping", "Poles", "Barrels", "Steer Riding", "Chute Dogging", "Dally Team Roping", "Tie Down Roping"];
                
                return $faker->randomElement( $events );
            }            
            return $faker->words( $faker->numberBetween(2, 3), true ) . ' event';
        },
        
        'description' => $faker->sentence,
    ];
});


$factory->state(Event::class, 'team-roping', [
    'team_roping' => true
]);