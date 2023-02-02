<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToAcademicQualificationsTable extends Migration
{
    public function up()
    {
        Schema::table('academic_qualifications', function (Blueprint $table) {
            $table->unsignedBigInteger('name_id')->nullable();
            $table->foreign('name_id', 'name_fk_7962698')->references('id')->on('qualification_levels');
            $table->unsignedBigInteger('board_id')->nullable();
            $table->foreign('board_id', 'board_fk_7962727')->references('id')->on('boards');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_7962736')->references('id')->on('users');
        });
    }
}
