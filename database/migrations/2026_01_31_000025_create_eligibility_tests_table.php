<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEligibilityTestsTable extends Migration
{
    public function up()
    {
        Schema::create('eligibility_tests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('agency');
            $table->date('year');
            $table->string('subject');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
