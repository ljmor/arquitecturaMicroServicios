<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
*/

$factory->define(App\Interaction::class, function (Faker\Generator $faker) {
    return [
        'book_id' => $faker->numberBetween(1, 10),
        'interaction_type' => $faker->randomElement(['view', 'click', 'purchase', 'wishlist']),
        'session_id' => $faker->uuid,
    ];
});
