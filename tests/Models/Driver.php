<?php

namespace Kamel\Auditable\Tests\Models;

use Kamel\Auditable\Auditable;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use Auditable;

    protected $fillable = ['name', 'email', 'age'];
}
