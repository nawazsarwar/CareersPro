<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToProfilesTable extends Migration
{
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_7957325')->references('id')->on('users');
            $table->unsignedBigInteger('marital_status_id')->nullable();
            $table->foreign('marital_status_id', 'marital_status_fk_7957348')->references('id')->on('marital_statuses');
            $table->unsignedBigInteger('disability_type_id')->nullable();
            $table->foreign('disability_type_id', 'disability_type_fk_7957370')->references('id')->on('disability_types');
            $table->unsignedBigInteger('religion_id')->nullable();
            $table->foreign('religion_id', 'religion_fk_7957437')->references('id')->on('religions');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id', 'category_fk_7957438')->references('id')->on('categories');
            $table->unsignedBigInteger('caste_id')->nullable();
            $table->foreign('caste_id', 'caste_fk_7957439')->references('id')->on('castes');
            $table->unsignedBigInteger('nationality_id')->nullable();
            $table->foreign('nationality_id', 'nationality_fk_7957597')->references('id')->on('countries');
            $table->unsignedBigInteger('district_of_birth_id')->nullable();
            $table->foreign('district_of_birth_id', 'district_of_birth_fk_7957598')->references('id')->on('postal_codes');
            $table->unsignedBigInteger('state_of_birth_id')->nullable();
            $table->foreign('state_of_birth_id', 'state_of_birth_fk_7957599')->references('id')->on('provinces');
            $table->unsignedBigInteger('domicile_state_id')->nullable();
            $table->foreign('domicile_state_id', 'domicile_state_fk_7957600')->references('id')->on('provinces');
            $table->unsignedBigInteger('domicile_district_id')->nullable();
            $table->foreign('domicile_district_id', 'domicile_district_fk_7957601')->references('id')->on('postal_codes');
        });
    }
}
