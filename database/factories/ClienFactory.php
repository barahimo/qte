<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Client;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    $nom_client = $faker->realText(67);
    return [
        'nom_client' =>  $nom_client,
        'adresse' => $faker->text(67),
        'ICE' => Str::ICE( $nom_client, '-'),
        'solde' => $faker->realText(67),
        'code_client' => $faker->realText (67),


    ];
});
