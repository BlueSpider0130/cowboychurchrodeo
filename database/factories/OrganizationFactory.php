<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Organization;
use App\OrganizationProfile;
use Faker\Generator as Faker;

$factory->define(Organization::class, function (Faker $faker) {
    return [
        'name' => "{$faker->company} organization",       
        'address_line_1' => $faker->streetAddress,
        'address_line_2' => $faker->boolean ? $faker->secondaryAddress : null, 
        'city' => $faker->city,
        'state' => $faker->boolean ? $faker->stateAbbr : null,
        'postcode' => $faker->postcode,
        'country_code' => $faker->countryCode,
        'phone' => $faker->phoneNumber,
        'email' => $faker->email, 
        'admin_notes' => $faker->sentence,

        'site_description' => $faker->paragraph,
        'site_title' => "{$faker->word} {$faker->word} organization",

        // 'site_logo_path',
        // 'site_banner_path',
        // 'site_header_banner_show',

        'site_font_family' => $faker->randomElement(['Arial', 'Courier New', 'Georgia', 'Times New Roman', 'Verdana']), 
        'site_font_size' => $faker->numberBetween(8, 24), 
        'site_text_color' => $faker->hexcolor, 
        'site_background_color' => $faker->hexcolor, 

        'site_header_font_family' => $faker->randomElement(['Arial', 'Courier New', 'Georgia', 'Times New Roman', 'Verdana']), 
        'site_header_font_size' => $faker->numberBetween(8, 24), 
        'site_header_text_color' => $faker->hexcolor, 
        'site_header_background_color' => $faker->hexcolor, 
        
        'site_footer_show' => true,
        'site_footer_content' => $faker->sentence,
        'site_footer_font_family' => $faker->randomElement(['Arial', 'Courier New', 'Georgia', 'Times New Roman', 'Verdana']), 
        'site_footer_font_size'=> $faker->numberBetween(8, 24), 
        'site_footer_text_color' => $faker->hexcolor, 
        'site_footer_background_color' => $faker->hexcolor, 
    ];
});
