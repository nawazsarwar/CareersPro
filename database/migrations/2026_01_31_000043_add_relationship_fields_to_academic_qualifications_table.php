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
            $table->foreign('name_id', 'name_fk_8863709')->references('id')->on('qualification_levels');
            $table->unsignedBigInteger('board_id')->nullable();
            $table->foreign('board_id', 'board_fk_8863714')->references('id')->on('boards');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_8863723')->references('id')->on('users');
        });
    }
}
