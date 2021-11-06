<?php

use Faker\Generator as Faker;
use Kamel\Auditable\Tests\Models\Driver;

/*
|--------------------------------------------------------------------------
| Audit Factories
|--------------------------------------------------------------------------
|
*/

$factory->define(Driver::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'age' => $faker->numberBetween(25, 60)
    ];
});
