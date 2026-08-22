<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeignVisitsTable extends Migration
{
    public function up()
    {
        Schema::create('foreign_visits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->string('duration');
            $table->string('purpose');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
