<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Group;
use App\Organization;
use Faker\Generator as Faker;

$factory->define(Group::class, function (Faker $faker) {

    return [
        'organization_id' => function() {
            return factory(Organization::class)->create()->id;
        }, 

        'name' => function() use ($faker) {
             return $faker->numberBetween(5, 18)." & ".$faker->randomElement(['up', 'under']);
        }, 
        
        'description' => $faker->sentence,
    ];
});