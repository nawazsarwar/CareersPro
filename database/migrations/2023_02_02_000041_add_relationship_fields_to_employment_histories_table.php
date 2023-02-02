<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToEmploymentHistoriesTable extends Migration
{
    public function up()
    {
        Schema::table('employment_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_7962844')->references('id')->on('users');
        });
    }
}
