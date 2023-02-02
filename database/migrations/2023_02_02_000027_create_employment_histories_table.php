<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmploymentHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('employment_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('employer');
            $table->string('type')->nullable();
            $table->string('designation');
            $table->date('from');
            $table->date('to')->nullable();
            $table->string('responsibilities');
            $table->string('reason_for_leaving');
            $table->string('pay_band');
            $table->string('basic_pay');
            $table->string('gross_pay');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
