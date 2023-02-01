<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTypesTable extends Migration
{
    public function up()
    {
        Schema::create('post_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('pdf_template');
            $table->string('submission_venue')->nullable();
            $table->string('status')->nullable();
            $table->longText('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
