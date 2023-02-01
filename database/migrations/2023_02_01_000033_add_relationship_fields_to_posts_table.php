<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToPostsTable extends Migration
{
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedBigInteger('advertisement_id')->nullable();
            $table->foreign('advertisement_id', 'advertisement_fk_7957103')->references('id')->on('advertisements');
            $table->unsignedBigInteger('posttype_id')->nullable();
            $table->foreign('posttype_id', 'posttype_fk_7957104')->references('id')->on('post_types');
            $table->unsignedBigInteger('added_by_id')->nullable();
            $table->foreign('added_by_id', 'added_by_fk_7957120')->references('id')->on('users');
        });
    }
}
