<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Contestant;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;

$factory->define(Contestant::class, function (Faker $faker) {
    return [
        'organization_id' => function() {
            return factory( \App\Organization::class )->create()->id;
        },

        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'birthdate'  => $faker->date(), 
        'photo_path' => function() use ($faker) {
            return UploadedFile::fake()->image($faker->word.$faker->randomNumber.'.jpg')->store('contestants', 'public');
        },        
        'address_line_1' => $faker->buildingNumber." ".$faker->streetName,
        'address_line_2' => $faker->secondaryAddress,
        'city'           => $faker->city,
        'state'          => $faker->state, 
        'postcode'       => $faker->postcode,        
    ];
});

$factory->state(Contestant::class, 'with-user', []);
$factory->afterCreatingState( Contestant::class, 'with-user', function ($contestant, $faker) {
    $contestant->users()->attach( factory( \App\User::class )->create()->id );
});
