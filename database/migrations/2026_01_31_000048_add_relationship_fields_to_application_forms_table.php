<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToApplicationFormsTable extends Migration
{
    public function up()
    {
        Schema::table('application_forms', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_10797842')->references('id')->on('users');
            $table->unsignedBigInteger('advertisement_id')->nullable();
            $table->foreign('advertisement_id', 'advertisement_fk_10797845')->references('id')->on('advertisements');
            $table->unsignedBigInteger('post_id')->nullable();
            $table->foreign('post_id', 'post_fk_10797847')->references('id')->on('posts');
            $table->unsignedBigInteger('disability_type_id')->nullable();
            $table->foreign('disability_type_id', 'disability_type_fk_10797856')->references('id')->on('disability_types');
            $table->unsignedBigInteger('religion_id')->nullable();
            $table->foreign('religion_id', 'religion_fk_10797859')->references('id')->on('religions');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id', 'category_fk_10797860')->references('id')->on('categories');
            $table->unsignedBigInteger('caste_id')->nullable();
            $table->foreign('caste_id', 'caste_fk_10797861')->references('id')->on('castes');
            $table->unsignedBigInteger('nationality_id')->nullable();
            $table->foreign('nationality_id', 'nationality_fk_10797863')->references('id')->on('countries');
            $table->unsignedBigInteger('domicile_state_id')->nullable();
            $table->foreign('domicile_state_id', 'domicile_state_fk_10797867')->references('id')->on('provinces');
            $table->unsignedBigInteger('scrutinized_by_id')->nullable();
            $table->foreign('scrutinized_by_id', 'scrutinized_by_fk_10797880')->references('id')->on('users');
            $table->unsignedBigInteger('eligibility_updated_by_id')->nullable();
            $table->foreign('eligibility_updated_by_id', 'eligibility_updated_by_fk_10797884')->references('id')->on('users');
        });
    }
}
