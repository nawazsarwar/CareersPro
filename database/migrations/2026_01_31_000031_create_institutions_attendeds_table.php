<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionsAttendedsTable extends Migration
{
    public function up()
    {
        Schema::create('institutions_attendeds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_of_school');
            $table->string('name_of_college');
            $table->integer('year_of_joining');
            $table->integer('year_of_leaving')->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
