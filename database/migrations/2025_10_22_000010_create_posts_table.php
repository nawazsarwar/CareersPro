<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('serial_no')->nullable();
            $table->string('title');
            $table->string('subject')->nullable();
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->integer('vacancies');
            $table->string('location');
            $table->string('pay_level');
            $table->string('pay_range');
            $table->decimal('fee', 15, 2);
            $table->datetime('opening_date')->nullable();
            $table->datetime('closing_date')->nullable();
            $table->datetime('payment_closing_date')->nullable();
            $table->integer('withdrawn');
            $table->integer('status');
            $table->longText('remarks')->nullable();
            $table->date('test_date')->nullable();
            $table->string('test_reporting_time')->nullable();
            $table->string('gate_closing_time')->nullable();
            $table->string('scheduled_test_start')->nullable();
            $table->string('test_duration')->nullable();
            $table->date('interview_date')->nullable();
            $table->string('interview_time')->nullable();
            $table->string('interview_venue')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
