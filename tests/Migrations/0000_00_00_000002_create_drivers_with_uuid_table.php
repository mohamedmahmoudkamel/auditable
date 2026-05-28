<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDriversWithUuidTable extends Migration
{
    public function up()
    {
        Schema::create('drivers_with_uuid', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email');
            $table->smallInteger('age');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('drivers_with_uuid');
    }
}
