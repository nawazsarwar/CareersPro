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
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->integer('vacancies');
            $table->string('location');
            $table->string('pay_level');
            $table->string('pay_range');
            $table->decimal('fee', 15, 2);
            $table->datetime('open_date')->nullable();
            $table->datetime('last_date')->nullable();
            $table->datetime('payment_last_date')->nullable();
            $table->integer('withdrawn');
            $table->integer('status');
            $table->longText('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
