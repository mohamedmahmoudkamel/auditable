<?php

namespace Kamel\Auditable\Tests\Models;

use Kamel\Auditable\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Kamel\Auditable\Tests\Factories\DriverFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Driver extends Model
{
    use Auditable, HasFactory;

    protected $fillable = ['name', 'email', 'age'];


    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return DriverFactory::new();
    }
}
