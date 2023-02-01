<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdressesTable extends Migration
{
    public function up()
    {
        Schema::create('adresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->string('house_no');
            $table->string('street');
            $table->string('landmark');
            $table->string('locality')->nullable();
            $table->string('city')->nullable();
            $table->string('district');
            $table->integer('status');
            $table->longText('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
