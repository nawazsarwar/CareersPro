<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostalCodesTable extends Migration
{
    public function up()
    {
        Schema::create('postal_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('locality')->nullable();
            $table->integer('code');
            $table->string('sub_district')->nullable();
            $table->string('district');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
