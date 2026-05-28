<?php

namespace Kamel\Auditable\Tests\Factories;

use Illuminate\Database\Eloquent\Model;
use Kamel\Auditable\Tests\Models\DriverWithUuid;
use Illuminate\Database\Eloquent\Factories\Factory;

class DriverWithUuidFactory extends Factory
{
    protected $model = DriverWithUuid::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'email' => fake()->email,
            'age' => fake()->numberBetween(25, 60),
        ];
    }
}
