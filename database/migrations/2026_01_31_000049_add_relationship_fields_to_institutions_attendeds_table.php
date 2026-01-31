<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToInstitutionsAttendedsTable extends Migration
{
    public function up()
    {
        Schema::table('institutions_attendeds', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_10797897')->references('id')->on('users');
            $table->unsignedBigInteger('university_board_id')->nullable();
            $table->foreign('university_board_id', 'university_board_fk_10797900')->references('id')->on('boards');
        });
    }
}
