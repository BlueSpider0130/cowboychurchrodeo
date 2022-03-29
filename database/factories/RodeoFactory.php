<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Rodeo;
use Faker\Generator as Faker;

$factory->define(Rodeo::class, function (Faker $faker, $data ) {
    
    $series = isset($data['series_id'])  ?  \App\Series::find( $data['series_id'] )  :  null;

    if( $series && $series->starts_at )
    {
        $start = $series->ends_at
                    ? $faker->dateTimeBetween( $series->starts_at, $series->ends_at )
                    : $faker->dateTimeBetween( $series->starts_at, "+180 days");

        $end = $series->ends_at
                    ? $faker->dateTimeBetween( $start, $series->ends_at )
                    : $faker->dateTimeBetween( $start, "+180 days");
    }
    else
    {
        $start = $faker->dateTimeBetween("-180 days", "+180 days");
        $end   = $faker->dateTimeBetween($start, "+180 days");    
    }

    return [

        'organization_id' => function() {
            return factory( \App\Organization::class )->create()->id;
        }, 

        'name' => $faker->words( $faker->numberBetween(2, 3), true ) . ' rodeo', 
        'starts_at' => $start,
        'ends_at' => $end,
    ];

});


/**
 * Adds entry fee.
 */
$factory->state(Rodeo::class, 'with-entry-fee', function ($faker) {
    return [
        'entry_fee' => $faker->numberBetween(1, 1000),
    ];
});


/**
 * No start and end dates.
 */
$factory->state(Rodeo::class, 'tba', function ($faker) {
    return [
        'starts_at' => null,
        'ends_at'   => null
    ];
});


/**
 * No end date.
 */
$factory->state(Rodeo::class, 'tba-end', function ($faker) {
    return [
        'ends_at' => null
    ];
});


/**
 * Future start date. i.e. rodeo scheduled to start in future.
 */
$factory->state(Rodeo::class, 'scheduled', function ($faker) {
    
    $startsAt = \Carbon\Carbon::now()->addDays( $faker->numberBetween(10, 100) );

    return [
        'starts_at' => $startsAt,
        'ends_at' => null
    ];

});


/**
 * Past end date. i.e. rodeo ended.
 */
$factory->state(Rodeo::class, 'ended', function ($faker) {

    $endsAt = \Carbon\Carbon::now()->subDays( $faker->numberBetween(10, 100) );

    return [
        'ends_at' => $endsAt
    ];

});


/**
 * Registration open and close date-time set.
 */
$factory->state(Rodeo::class, 'open', function ($faker) {

    $startsAt = \Carbon\Carbon::now()->addDays( $faker->numberBetween(10, 100) );
    $closesAt = $startsAt->copy()->subDays(1);
    $opensAt = $closesAt->copy()->subDays(10);

    return [
        'starts_at' => $startsAt,
        'ends_at' => null, 
        'opens_at' => $opensAt,
        'closes_at' => $closesAt,
    ];

});


/**
 * Registration close date in past.
 */
$factory->state(Rodeo::class, 'closed', function ($faker) {
    
    $startsAt = \Carbon\Carbon::now()->addDays( $faker->numberBetween(10, 100) );
    $closesAt = \Carbon\Carbon::now()->subDays(1);
    $opensAt = $closesAt->copy()->subDays(10);

    return [
        'starts_at' => $startsAt,
        'ends_at' => null, 
        'opens_at' => $opensAt,
        'closes_at' => $closesAt,
    ];

});


/**
 * Registration open date in future.
 */
$factory->state(Rodeo::class, 'open-scheduled', function ($faker) {
    
    $startsAt = \Carbon\Carbon::now()->addDays( $faker->numberBetween(10, 100) );
    $opensAt = \Carbon\Carbon::now()->addDays( $faker->numberBetween(5, 10) );

    return [
        'starts_at' => $startsAt,
        'ends_at' => null, 
        'opens_at' => $opensAt,
    ];

});
