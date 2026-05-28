<?php

namespace Kamel\Auditable\Tests\Models;

use Kamel\Auditable\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kamel\Auditable\Tests\Factories\DriverWithUuidFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class DriverWithUuid extends Model
{
    use Auditable, HasFactory, HasUuids;

    protected $table = 'drivers_with_uuid';
    protected $fillable = ['name', 'email', 'age'];

    protected static function newFactory(): Factory
    {
        return DriverWithUuidFactory::new();
    }
}
