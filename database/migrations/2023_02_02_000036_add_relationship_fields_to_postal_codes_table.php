<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToPostalCodesTable extends Migration
{
    public function up()
    {
        Schema::table('postal_codes', function (Blueprint $table) {
            $table->unsignedBigInteger('province_id')->nullable();
            $table->foreign('province_id', 'province_fk_7957564')->references('id')->on('provinces');
        });
    }
}
