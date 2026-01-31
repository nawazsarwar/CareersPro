<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTraedsTable extends Migration
{
    public function up()
    {
        Schema::create('traeds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('teaching_at_bachelors_level_in_years')->nullable();
            $table->integer('teaching_at_masters_level_in_years')->nullable();
            $table->integer('research_at_masters_level_in_years')->nullable();
            $table->integer('research_at_post_doctorals_level_in_years')->nullable();
            $table->integer('experience_in_educational_administration_in_years')->nullable();
            $table->integer('any_other_administrative_experience_in_years')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
