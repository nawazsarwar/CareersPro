<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicQualificationsTable extends Migration
{
    public function up()
    {
        Schema::create('academic_qualifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('course');
            $table->date('year');
            $table->string('division');
            $table->float('percentage', 5, 2)->nullable();
            $table->float('cgpa', 5, 2);
            $table->string('subjects');
            $table->string('title');
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
