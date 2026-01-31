<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvincesTable extends Migration
{
    public function up()
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->nullable();
            $table->string('name')->nullable();
            $table->string('iso_3166_2_in')->nullable();
            $table->string('vehicle_code')->nullable();
            $table->string('zone')->nullable();
            $table->string('capital')->nullable();
            $table->string('largest_city')->nullable();
            $table->integer('statehood')->nullable();
            $table->string('population')->nullable();
            $table->string('area')->nullable();
            $table->string('official_languages')->nullable();
            $table->string('additional_official_languages')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
