<?php

namespace Kamel\Auditable\Tests\Factories;

use Illuminate\Database\Eloquent\Model;
use Kamel\Auditable\Tests\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

class DriverFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Model>
     */
    protected $model = Driver::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'email' => fake()->email,
            'age' => fake()->numberBetween(25, 60)
        ];
    }
}
