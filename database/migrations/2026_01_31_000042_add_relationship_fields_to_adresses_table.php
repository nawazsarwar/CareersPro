<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToAdressesTable extends Migration
{
    public function up()
    {
        Schema::table('adresses', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_8863691')->references('id')->on('users');
            $table->unsignedBigInteger('postal_code_id')->nullable();
            $table->foreign('postal_code_id', 'postal_code_fk_8863685')->references('id')->on('postal_codes');
            $table->unsignedBigInteger('province_id')->nullable();
            $table->foreign('province_id', 'province_fk_8863687')->references('id')->on('provinces');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id', 'country_fk_8863688')->references('id')->on('countries');
        });
    }
}
