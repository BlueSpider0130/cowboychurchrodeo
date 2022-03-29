<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Document;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;

$factory->define(Document::class, function (Faker $faker) {

    $filename = $faker->word.'-'.$faker->randomNumber.'.'.$faker->randomElement(['pdf', 'doc', 'docm', 'docx', 'odf']);

    return [
        'organization_id' => function() {
            return factory( \App\Organization::class )->create()->id;
        },        

        'path' => function() use ($faker, $filename) {            
            return UploadedFile::fake()->create( $filename, 1 )->store('documents');
        },

        'filename'    => $filename,
        'name'        => $faker->words(2, true), 
        'description' => $faker->sentence,       
    ];
});
